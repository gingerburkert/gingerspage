<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

/**
* Joomla article element class
* instead of using direct method use class
*/
class QuixJoomlSingleArticleElementClass
{   
    public static function getListJoomlaArticle() {

    $db = JFactory::getDbo();
    $query = $db->getQuery(true)
                ->select('id, title')
                ->from('#__content');

    $db->setQuery($query);
    $items = $db->loadObjectList();
    return $items;
  } 
  
  // public static function getListJoomlaArticle() {

  //   $app = JFactory::getApplication();
  //   if(!$app->isAdmin()){
  //       return array();
  //   }
    
  //   JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');
  //   // Get an instance of the generic articles model
  //   $model = JModelLegacy::getInstance( 'Articles', 'ContentModel', [ 'ignore_request' => true ] );
    
  //   // Set the filters based on the article params
  //   $model->setState( 'list.start', 0 );
  //   $model->setState( 'list.limit', 9999 );
  //   $model->setState( 'filter.published', 1 );
  //   $model->setState( 'filter.category_id', 0 );

  //   $params = JComponentHelper::getParams( 'com_content' );

  //   // Set ordering
  //   $model->setState( 'list.ordering', 'a.ordering' );
  //   $model->setState( 'list.direction', 'ASC' );

  //   // Load the parameters.
  //   $model->setState('params', $params);
    

  //   // Retrieve Content
  //   $items = $model->getItems();

  //   return $items;
  // } 

  public static function getJoomlaArticle($id) {

    JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

    // Get an instance of the generic articles model
    $model = JModelLegacy::getInstance( 'Article', 'ContentModel', [ 'ignore_request' => true ] );
    $model->setState( 'filter.published', 1 );

    // Access filter
    $params = JComponentHelper::getParams( 'com_content' );
    $access = ! $params->get( 'show_noauth' );
    $model->setState( 'filter.access', $access );

    // Load the parameters.
    $app = JFactory::getApplication('site');
    if(!$app->isAdmin()){
        $params = $app->getParams();
    }
    $model->setState('params', $params);

    // Retrieve Content
    $items = $model->getItem($id);

    return $items;
  }  
}
