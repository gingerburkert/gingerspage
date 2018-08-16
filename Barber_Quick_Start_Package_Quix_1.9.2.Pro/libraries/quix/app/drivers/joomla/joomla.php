<?php
/** FIXME */
use Joomla\Http\http;
JLoader::register( 'QuixFrontendHelper', JPATH_SITE . '/components/com_quix/helpers/quix.php' );

// Load module by id
if ( !function_exists( 'qxModuleById' ) ) {
  function qxModuleById( $id, $style = 'raw' ) {

    $db = JFactory::getDBo();
    $query = $db->getQuery( true );
    $query->select( '*' )
          ->from( '#__modules' )
          ->where( 'published = ' . 1 )
          ->where( 'id = ' . $id );
    $db->setQuery( $query );
    $module = $db->loadObject();
    
    // check if module not found
    if(!isset($module->id)) return;

    $mparams = json_decode($module->params);
    $params = array( 'style' => ( isset($mparams->style) ? $mparams->style : $style) );
    $enabled = JModuleHelper::isEnabled( $module->module);
    if($enabled){
      $moduleinfo = JModuleHelper::getModule( $module->module, $module->title );
      $info = (object) array_merge((array) $moduleinfo, (array) $module);
      return JModuleHelper::renderModule( $info, $params );
    }
    else{
      return;
    }
  }
}

if ( !function_exists( 'qxGetCollections' ) ) {
  function qxGetCollections( $details = false ) {
    JModelLegacy::addIncludePath( JPATH_SITE . '/administrator/components/com_quix/models', 'QuixModel' );

    // Get an instance of the generic articles model
    $model = JModelLegacy::getInstance( 'Collections', 'QuixModel', array( 'ignore_request' => true ) );

    // Set the filters based on the module params
    $model->setState( 'list.start', 0 );
    $model->setState( 'list.limit', 999 );

    if ( !$details ) {
      $model->setState( 'list.select', 'a.id, a.uid, a.title, a.type' );
    }

    $model->setState( 'filter.state', 1 );

    // Access filter
    $access = !JComponentHelper::getParams( 'com_quix' )->get( 'show_noauth' );
    $authorised = JAccess::getAuthorisedViewLevels( JFactory::getUser()->get( 'id' ) );
    $model->setState( 'filter.access', $access );

    // Retrieve Content
    return $model->getItems();
  }
}

if ( !function_exists( 'qxGetElementsInfo' ) ) {
  function qxGetElementsInfo( $details = false ) {
    JModelLegacy::addIncludePath( JPATH_SITE . '/administrator/components/com_quix/models', 'QuixModel' );

    // Get an instance of the generic articles model
    $model = JModelLegacy::getInstance( 'Elements', 'QuixModel', array( 'ignore_request' => true ) );

    // Set the filters based on the module params
    $model->setState( 'list.start', 0 );
    $model->setState( 'list.limit', 999 );
//    $model->setState( 'filter.state', 1 );


    // Retrieve Content
    return $model->getItems();
  }
}

// $field = array,
// $name = string > name of the icon field
if ( !function_exists( 'get_icon' ) ) {
  function get_icon( $field = [], $name = 'icon' ) {
    $result = [];
    $icon   = [];
    if(!is_array($field[$name]))
    {
      $icon = json_decode($field[$name], true);
    }

    // if(!is_array($field['icon']) && !count($icon)):
    if(!count($icon)):
      $result['class'] = $field[$name];
      $result['content'] = '';
    else:
      if(!isset($icon['iconsType']) or empty($icon['iconsType'])) return;
    
      // print_r($icon);die;
      $dispatcher = JDispatcher::getInstance();
      $dispatcher->trigger('onQuixPrepareFont', array($icon['iconsType']));
      if($icon['renderer'] == "content-based"):
        $result['class'] = $icon['className'];
        $result['content'] = $icon['name'];
      else:
        // only else as we dont have any other option
        // elseif($field['renderer'] == "class-based"):

        $result['class'] = $icon['name'];
        $result['content'] = '';
      endif;
    endif;
      
    return $result;

  }
}

function quix_default_template() {
  $db = JFactory::getDBO();
  $query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
  $db->setQuery( $query );
  return $db->loadResult();
}

function qxGetCollectionById( $id ) {
  $app = JFactory::getApplication();
  if ( !$app->isAdmin() ) {
    JModelLegacy::addIncludePath( JPATH_SITE . '/components/com_quix/models', 'QuixModel' );
    require_once JPATH_SITE . '/components/com_quix/helpers/quix.php';

    $model = JModelLegacy::getInstance( 'Collection', 'QuixModel', array( 'ignore_request' => true ) );
    // Retrieve Content
    $item = $model->getData( $id );
  } else {
    $model = JModelLegacy::getInstance( 'Collection', 'QuixModel', array( 'ignore_request' => true ) );
    // Retrieve Content
    $item = $model->getItem( $id );

  }

  return $item;
}

function qxGetCollectionInfoById( $id ) {
    JModelLegacy::addIncludePath( JPATH_SITE . '/components/com_quix/models', 'QuixModel' );
    require_once JPATH_SITE . '/components/com_quix/helpers/quix.php';
    $model = JModelLegacy::getInstance( 'Collection', 'QuixModel', array( 'ignore_request' => true ) );
    // Retrieve Content
    $item = $model->getData( $id );

  return $item;
}

/**
 * @return mixed
 */
function qxGetComponentInfo() {
  $extension = JTable::getInstance( 'extension' );
  $id = $extension->find( array( 'element' => 'com_quix' ) );
  $extension->load( $id );
  $componentInfo = json_decode( $extension->manifest_cache, true );

  return $componentInfo;
}

function qxGetBlocks()
{  
  $config = JComponentHelper::getParams('com_quix');
  $api_https = $config->get('api_https', 1);
  
  // absolute url of list json
  $url = ($api_https ? 'https' : 'http') . '://getquix.net/index.php?option=com_quixblocks&view=category&catid=0&tmpl=component&format=json';
  
  $cache = JFactory::getCache('libquix');
  $cache->setCaching( 1 );
  $result  = $cache->call( "getResponsefromAPI",  $url);
  return ($result ? $result : '{"success": false}');
}

function getResponsefromAPI($url) 
{
  $process = true;
  // Get the handler to download the blocks
  try
  {
    $http = JHttpFactory::getHttp(null, array('curl', 'stream'));
  }
  catch (RuntimeException $e)
  {
    $process = false;
  }
  
  // get from local  
  if(!$process) return getResponsefromLocal();

  // Download the json of blocks
  try
  {
    $result = $http->get($url);
  }
  catch (RuntimeException $e)
  {
    $process = false;
  }

  // get from local  
  if(!$process) return getResponsefromLocal();

  if (!$result || ($result->code != 200 && $result->code != 310))
  {
    $process = false;
  }

  // get from local  
  if(!$process) return getResponsefromLocal();

  // now validate the response
  try {
    json_decode($result->body);    
    if(json_last_error() == JSON_ERROR_NONE)
    {
      return $result->body;
    }else{
      return getResponsefromLocal();
    }
  } catch (Exception $e) {
    return getResponsefromLocal();
  }

}

/**
* Method getResponsefromLocal
* @param none
* @return json
*/
function getResponsefromLocal()
{
  if (file_exists(JPATH_SITE . '/media/quix/json/blocks.json'))
  {
    jimport('joomla.filesystem.file');

    $json = JFile::read(JPATH_SITE . '/media/quix/json/blocks.json');
    return $json;
  }
  else
  {
    JError::raiseWarning('', JText::_('COM_QUIX_BLOCKS_MISSING_FILES'));
    return false;
  }
  
}