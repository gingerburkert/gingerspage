<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/simple_html_dom.php';
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderckfront.php';

class PagebuilderckModelPage extends JModelAdmin {

	var $_item = null;

	protected $_context = 'com_pagebuilderck.page';

	public $styleTags;

	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) JFactory::getApplication()->input->get('id', 0, 'int');

		if (empty($this->_item))
		{
			try
			{
				$db = $this->getDbo();
				// it is a new item creation
				if ($pk === 0) {
					$user = JFactory::getUser();
					// check that the user has the rights to edit
					$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));
					if ($authorised !== true)
					{
						JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
						return false;
					}
					// Get a level row instance.
					$data = $this->getTable();

					// Attempt to load the row.
					$data->load($pk);
				}
				else {
					$query = $db->getQuery(true);
					$query->select('*');
					$query->from('#__pagebuilderck_pages AS a');
					$query->where('a.state = 1');
					$query->where('a.id = ' . (int) $pk);
					$db->setQuery($query);

					$data = $db->loadObject();

					// if (empty($data))
					// {
						// return JError::raiseError(404, JText::_('COM_PAGEBUILDERCK_ERROR_PAGE_NOT_FOUND'));
					// }
				}
				$this->_item = $data;
			
				if (! empty($this->_item)) {
					// transform params to JRegistry object
					if (isset($this->_item->params)) $this->_item->params = new JRegistry($this->_item->params);

					if ($pk !== 0) {
						// counter for hits
						$sql = "UPDATE #__pagebuilderck_pages SET hits = hits + 1 WHERE id= " . $this->_item->id;
						$db->setQuery($sql) ;
						$db->query();
					}
				}
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item = false;
				}
			}
		}

		if (isset($this->_item->htmlcode) && $this->_item->htmlcode) {
			// replace the root path for all elements
			$this->_item->htmlcode = trim(str_replace("|URIROOT|", JUri::root(true), $this->_item->htmlcode));
			// active the content plugins interaction
			if ($this->_item->params->get('contentprepare', '0')) {
				JPluginHelper::importPlugin('content');
				$this->_item->htmlcode = JHtml::_('content.prepare', $this->_item->htmlcode, '', 'com_pagebuilderck.page');
			}

			// pass through the html code to convert what is needed
//			if (JFactory::getApplication()->input->get('layout', '', 'cmd') === 'default'
//			|| JFactory::getApplication()->input->get('layout', '', 'cmd') === '') {
			if (JFactory::getApplication()->input->get('layout', '', 'cmd') !== 'edit') {
				$this->parseHtml($this->_item->htmlcode);
			}
		}

		return $this->_item;
	}

	public function parseHtml(&$htmlcode) {
		// replace the root path for all elements
		$htmlcode = trim(str_replace("|URIROOT|", JUri::root(true), $htmlcode));
		// replace the tags for W3C compliance
		// $htmlcode = trim(str_replace('<div class="ckstyle"><style>', '<div class="ckstyle"><style scoped>', $htmlcode)); // WARNING : scoped limits to the ckstyle parent !
		$htmlcode = str_replace('http://fonts.googleapis.com', '//fonts.googleapis.com', $htmlcode);
		$htmlcode = trim(str_replace('height=""', '', $htmlcode));
		$htmlcode = trim(str_replace('width=""', '', $htmlcode));
		// load the modules
		// <div class="modulerow" data-module="mod_menu" data-title="a propos de joomla!" data-id="23" style="cursor:pointer;">
		$regex2 = "#<div\s[^>]*class=\"modulerow([^\"]*)\"([^>]*)>(.*)<\/div>#siU"; // masque de recherche pour le tag
		$htmlcode = preg_replace_callback($regex2, array($this, 'replaceModule'), $htmlcode);

		// loop through elements to be replaced in frontend
		// $regex_type = "#<div\s[^>]*class=\"cktype([^\"]*)\"([^>]*)>(.*?)<\/div>#siU"; // masque de recherche pour le tag
		// $htmlcode = preg_replace_callback($regex_type, array($this, 'replaceElement'), $htmlcode);

		if ($htmlcode) {
			$html = str_get_html($htmlcode);
			
			// find all types in the page
			foreach($html->find('div.cktype') as $e) {
				$e->innertext = $this->replaceElement($e);
			}

			// find all google fonts, call them as stylesheet in the page header
			foreach($html->find('div.googlefontscall') as $e) {
				$regex = "#href=\"\s*[^>]*\?family=([^\"]*)\"[^>]*>#siU"; // replace all divs with class ckprops
				foreach(explode('<link', $e->innertext) as $call) {
					preg_match($regex, $call, $matches);
					$fontcalled = isset($matches[1]) && $matches[1] ? $matches[1] : '';
					if ($fontcalled) PagebuilderckFrontHelper::addStylesheet('//fonts.googleapis.com/css?family=' . $fontcalled);
					$e->innertext = '';
				}
			}

			$htmlcode = $html->save();
			$html->clear();
			unset($html);
		}

		// remove the id="googlefontscall" to avoid html error in frontend
		$htmlcode = preg_replace('/ id="googlefontscall"/', '', $htmlcode);
		$regexGooglefont = "#<div\s[^>]*googlefontscall([^\"]*)\"[^>]*>(.*)<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace_callback($regexGooglefont, array($this, 'replaceGooglefont'), $htmlcode);

		// remove all the settings values (that are not needed in front) (TODO with parser)
		$regex = "#<div\s[^>]*ckprops([^\"]*)\"[^>]*>[^<]*<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace($regex, '', $htmlcode);
		$htmlcode = preg_replace('/<div fieldslist="(.*?)"(.*?)><\/div>/', '', $htmlcode);

		$regexStyle = "#<div\s[^>]*ckstyle([^\"]*)\"[^>]*>(.*)<\/div>#siU"; // replace all divs with class ckprops
		// $htmlcode = preg_replace($regex, '', $htmlcode);
		$htmlcode = preg_replace_callback($regexStyle, array($this, 'replaceStyleTag'), $htmlcode);

		$regexStyle2 = "#<style\s[^>]*ckcolumnwidth([^\"]*)\"[^>]*>(.*)<\/style>#siU"; // replace all divs with class ckprops
		// $htmlcode = preg_replace($regex, '', $htmlcode);
		$htmlcode = preg_replace_callback($regexStyle2, array($this, 'replaceStyleTag'), $htmlcode);

		$doc = JFactory::getDocument();
		PagebuilderckFrontHelper::addStyleDeclaration($this->styleTags);
	}

	/*
	 * @param array the matching strings
	 * 
	 * return void
	 */
	public function replaceGooglefont(&$matches) {
		if (!isset($matches[2])) return;
		$fontfamilies = explode('<', $matches[2]);

		foreach ($fontfamilies as $fontfamily) {
			$fontfamily = trim($fontfamily);
			$fontfamily = str_replace('link href="', '', $fontfamily);
			$fontfamily = str_replace('" rel="stylesheet" type="text/css">', '', $fontfamily);

			if ($fontfamily) PagebuilderckFrontHelper::addStylesheet($fontfamily);
		}

		return;
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the module cdoe
	 */
	public function replaceStyleTag(&$matches) {

		if (!$matches[2]) return;
		$styleTag = trim($matches[2]);
		$styleTag = str_replace('<style>', '', $styleTag);
		$styleTag = str_replace('</style>', '', $styleTag);
		$styleTag = str_replace('&nbsp;', ' ', $styleTag);
		// var_dump($styleTag);
		if ($styleTag) $this->styleTags .= $styleTag;

		return '';
	}
	
	/*
	 * @param array the matching strings
	 * 
	 * return the module cdoe
	 */
	public function replaceModule(&$matches) {
		if (!$matches[2]) return;

		// look for the module ID
		$find = "#data-id=\"(.*?)\"#si"; // masque de recherche pour le tag
		preg_match($find, $matches[2], $result_id);
		if ($result_id && $result_id[1]) {
			return $this->renderModule($result_id[1]);
		}

		return '';
	}

	/*
	 * @param object the element
	 * 
	 * return the module cdoe
	 */
	public function replaceElement($e) {
		$type = $e->attr['data-type'];

		if ($type) {
			$new_e = $this->renderElement($type, $e);
			if ($new_e) {
				return $new_e;
			} else {
				return $e->innertext;
			}
		} else if ($type == 'audio') {
			return $this->renderAudioElement($e);
		} else {
			return '<p style="text-align:center;color:red;font-size:14px;">ERROR - PAGEBUILDER CK DEBUG : ELEMENT TYPE NOT FOUND</p>';
		}

		return $e->innertext;
	}

	/*
	 * @param object the element
	 * 
	 * return the element html code for html5 audio
	 */
	public function renderAudioElement($e) {
		$attrs = $e->find('.tab_audio');
		$params = PagebuilderckFrontHelper::createParamsFromElement($attrs);

		$audiosrc = PagebuilderckFrontHelper::getSource($params->get('audiourl'));
		$html ='<audio style="width:100%;box-sizing:border-box;max-width:100%;" controls src="' . $audiosrc . '" ' . ($params->get('autoplayyes') == 'checked' ? 'autoplay' : '') . '>'
				. 'Your browser does not support the audio element.'
				. '</audio>';

		$html2 = preg_replace('#<div class="audiock">(.*?)<\/div>#is', $html, $e->innertext);

		return $html2;
	}

	/*
	 * @param string the element type
	 * @param object the element
	 * 
	 * return the element html code
	 */
	public function renderElement($type, $e) {
		// check if there is a plugin for this type, and if it is enabled
		if ( !JPluginHelper::isEnabled('pagebuilderck', $type)) {
			return '';
		}
		$doc = JFactory::getDocument();

		JPluginHelper::importPlugin( 'pagebuilderck' );
		$dispatcher = JEventDispatcher::getInstance();
		$otheritems = $dispatcher->trigger( 'onPagebuilderckRenderItem' .  ucfirst($type) , array($e));

		ob_start();
		if (count($otheritems) == 1) {
			// load only the first instance found, because each plugin type must be unique
			// add override feature here, look in the template
			$template = JFactory::getApplication()->getTemplate();
			$overridefile = JPATH_ROOT . '/templates/' . $template . '/html/pagebuilderck/' . strtolower($type) . '.php';
			// var_dump($overridefile);die;
			if (file_exists($overridefile)) {
			// die('ok');
				$item = $e;
				include_once $overridefile;
			} else {
				// normal use
			$html = $otheritems[0];
			}
			echo $html;
		} else {
			echo '<p style="text-align:center;color:red;font-size:14px;">ERROR - PAGEBUILDER CK DEBUG : ELEMENT TYPE INSTANCE : ' . $type . '. Number of instances found : ' . count($otheritems) . '</p>';
		}
		$element_code = ob_get_clean();
		return $element_code;
	}

	/*
	 * @param int the module ID
	 * 
	 * return the module html code
	 */
	public function renderModule($module_id) {
	// var_dump($this->getModule($module_id));die;
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$mod		= $this->getModule($module_id);

		if (!$mod) return;
		// If the module without the mod_ isn't found, try it with mod_.
		// This allows people to enter it either way in the content
		// if (!isset($mod))
		// {
			// $name = 'mod_' . $module;
			// $mod  = JModuleHelper::getModule($name, $title);
		// }

		$params = array('style' => 'xhtml');
		ob_start();

		echo $renderer->render($mod, $params);

		$module_code = ob_get_clean();

		return $module_code;
	}

	public function getModule($module_id) {
		$app = JFactory::getApplication();
		$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int) $app->getClientId();

		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
			->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid')
			->from('#__modules AS m')
			->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
			->where('m.published = 1')
			->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
			->where('e.enabled = 1');

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
			->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')
			->where('m.access IN (' . $groups . ')')
			->where('m.client_id = ' . $clientId)
			->where('m.id = ' . (int) $module_id)
			// ->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');
			;

		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter())
		{
			$query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
		}

		// $query->order('m.position, m.ordering');

		// Set the query
		$db->setQuery($query);

		try
		{
			$module = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()), JLog::WARNING, 'jerror');

			return array();
		}

		return $module;
	}

	/**
	 * Module list
	 *
	 * @return  array
	 */
	public static function getModuleList()
	{
		$app = JFactory::getApplication();
		$Itemid = $app->input->getInt('Itemid');
		$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int) $app->getClientId();

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid')
			->from('#__modules AS m')
			->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
			->where('m.published = 1')
			->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
			->where('e.enabled = 1');

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
			->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')
			->where('m.access IN (' . $groups . ')')
			->where('m.client_id = ' . $clientId)
			->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');

		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter())
		{
			$query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
		}

		$query->order('m.position, m.ordering');

		// Set the query
		$db->setQuery($query);

		try
		{
			$modules = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()), JLog::WARNING, 'jerror');

			return array();
		}

		return $modules;
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null) {
		$app = JFactory::getApplication();
		if ($this->_item === null) {
			$this->_item = false;

			if (empty($id)) {
				$id = $app->input->get('id', '', 'int');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id)) {
				// Check published state.
				if ($published = $this->getState('filter.published')) {
					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}

		return $this->_item;
	}

	public function getTable($type = 'Page', $prefix = 'PagebuilderckTable', $config = array()) {
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/forms');
		// Get the form.
		$form = $this->loadForm('com_pagebuilderck.page', 'page', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() {
		$data = $this->getData();

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data) {

		$input = JFactory::getApplication()->input;
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('page.id');
		$user = JFactory::getUser();
		$data['htmlcode'] = $input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(JUri::root(true), "|URIROOT|", $data['htmlcode']);

		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new Registry;
			$registry->loadArray($data['options']);
			$data['params'] = (string) $registry;
		}

		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'page.' . $id);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_pagebuilderck');
		}

		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		$table = $this->getTable();
		$table->load($data['id']);
		// if ($table->name !== $data['name']) {
			// $this->changeTemplateName($table->name, $data['name']);
		// }

		// make a backup before save
		PagebuilderckHelper::makeBackup($this->getData($data['id']));

		if ($table->save($data) === true) {
			return $table->id;
		} else {
			return false;
		}
	}

}