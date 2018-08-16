<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Quix helper.
 *
 * @since  1.6
 */
class QuixHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		
		// JHtmlSidebar::addEntry(
		// 	JText::_('COM_QUIX_TITLE_DASHBOARD'),
		// 	'index.php?option=com_quix&view=dashboard',
		// 	$vName == 'dashboard'
		// );

		JHtmlSidebar::addEntry(
			JText::_('COM_QUIX_SIDEBAR_PAGES'),
			'index.php?option=com_quix&view=pages',
			$vName == 'pages'
		);

		// JHtmlSidebar::addEntry(
		// 	JText::_('JCATEGORIES'),
		// 	'index.php?option=com_categories&view=categories&extension=com_quix',
		// 	$vName == 'categories'
		// );
		
		JHtmlSidebar::addEntry(
			JText::_('COM_QUIX_SIDEBAR_COLLECTIONS'),
			'index.php?option=com_quix&view=collections',
			$vName == 'collections'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_QUIX_SIDEBAR_INTEGRATIONS'),
			'index.php?option=com_quix&view=integrations',
			$vName == 'integrations'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_QUIX_SIDEBAR_ELEMENTS_MANAGER'),
            'index.php?option=com_quix&view=elements',
            $vName == 'elements'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_QUIX_SIDEBAR_FILEMANAGER_MANAGER'),
			'index.php?option=com_quix&view=filemanager',
			$vName == 'filemanager'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_quix';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}	

	/**
	* Get group name using group ID
	* @param integer $group_id Usergroup ID
	* @return mixed group name if the group was found, null otherwise
	*/
	public static function getGroupNameByGroupId($group_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__usergroups')
			->where('id = ' . intval($group_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
	/*
	* to get update info
	* use layout to get alert structure
	*/

	public static function getUpdateStatus(){

		$update = self::checkUpdate();
		// print_r($update);die;
		if(isset($update->update_id) && $update->update_id)
		{
			$credentials = self::hasCredentials();
			// Instantiate a new JLayoutFile instance and render the layout
			$layout = new JLayoutFile('toolbar.update');
			return $layout->render(array('info' => $update, 'credentials' => $credentials));		
		}

		return;
	}

	/*
	* show warning
	* for free versions only
	*/

	public static function getFreeWarning(){
		jimport( 'joomla.form.form' );
		//##QUIX_CREATIONDATE##
		$form = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR.'/quix.xml');
		if($form->tag != 'pro'){
			$layout = new JLayoutFile('toolbar.freenotice');
			return $layout->render(array());		
		}

		return;
	}

	/*
	* show pro elements
	* for free versions only
	*/

	public static function getProElementBanner(){
		jimport( 'joomla.form.form' );
		//##QUIX_CREATIONDATE##
		$form = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR.'/quix.xml');
		if($form->tag != 'pro'){
			$layout = new JLayoutFile('blocks.elements');
			return $layout->render(array());		
		}

		return;
	}

	/*
	* Method isFreeQuix
	* @return boolian
	*/

	public static function isFreeQuix(){
		jimport( 'joomla.form.form' );
		$form = simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR.'/quix.xml');
		if($form->tag != 'pro'){
			return false;
		}

		return true;
	}

	/*
	* to get php warning
	* we require at least php 5.4
	*/

	public static function getPHPWarning(){

		if (version_compare(phpversion(), '5.5', '<')) {
			// Instantiate a new JLayoutFile instance and render the layout
			$layout = new JLayoutFile('toolbar.phpwarning');
			return $layout->render(array());		
		}

		return;
	}
	
	/*
	* check filemanager status
	* some special permission required for filemanager
	*/

	public static function checkFileManager()
	{

		try {
			// Create an instance of a default JHttp object.
			$http = JHttpFactory::getHttp();
			// Invoke the HEAD request.
			$response = $http->head(JUri::root() . 'media/quix/filemanager/index.php');

			// The response code is included in the "code" property.
			if($response->code == 403){
				// show warning or fix guide:
				// Instantiate a new JLayoutFile instance and render the layout
				$layout = new JLayoutFile('toolbar.filemanagerguide');
				return $layout->render(array());	
			}
			
		} catch (Exception $e) {
			// nothing to show now, lets ignore
			return;			
		}

		return;
	}

	/*
	* to get update info
	* use layout to get alert structure
	*/

	public static function checkUpdate(){
		// Get a database object.
		$db = JFactory::getDbo();

		// get extensionid
		$query = $db->getQuery(true)
					->select('extension_id')
					->from('#__extensions')
					->where($db->quoteName('type') . ' = ' . $db->quote('package'))
					->where($db->quoteName('element') . ' = ' . $db->quote('pkg_quix'));

		$db->setQuery($query);
		
		$extensionid = $db->loadResult();

		// get update_site_id
		$query = $db->getQuery(true)
					->select('*')
					->from('#__updates')
					->where($db->quoteName('extension_id') . ' = ' . $db->quote($extensionid))
					->where($db->quoteName('element') . ' = ' . $db->quote('pkg_quix'))
					->where($db->quoteName('type') . ' = ' . $db->quote('package'));
		$db->setQuery($query);
		
		return $db->loadObject();
		
	}

	public static  function hasCredentials()
	{
		$config	= JModelLegacy::getInstance('Config', 'QuixModel', array('ignore_request' => false));
		$config->generateState();
		return $config->getItem();		
	}

	public static  function randerSysMessage()
	{
		$layout = new JLayoutFile('toolbar.message');
		return $layout->render(array());
	}



	public static function getSystemInfo()
	{
		// $registry = new Registry(new JConfig);
		// $config = $registry->toArray();
		
		$info = array();
		$info['php_version'] = phpversion();
		$info['cache_writable'] = is_writable(JPATH_CACHE);
		$info['curl_support'] = extension_loaded('curl');
		$info['ctype_support'] = extension_loaded('ctype');
		$info['memory_limit'] = ini_get('memory_limit');

		return $info;
	}

	public static function cleanCache()
	{
		jimport('joomla.filesystem.file');
	    jimport('joomla.filesystem.folder');
	    jimport('joomla.filesystem.path');

	    $cssfiles = JFolder::files(JPATH_ROOT . '/media/quix/css');
		array_map(
			function ($file) {
				if($file == 'index.html') return;
				JFile::delete(JPATH_ROOT . '/media/quix/css/'. $file);
			}, 
			$cssfiles
		);

	    $jsfiles = JFolder::files(JPATH_ROOT . '/media/quix/js');		
		array_map(
			function ($file) {
				if($file == 'index.html') return;
				JFile::delete(JPATH_ROOT . '/media/quix/js/'. $file);
			},
			$jsfiles
		);
		
		// Clear relavent cache
		QuixHelper::cachecleaner('com_quix');
		QuixHelper::cachecleaner('mod_quix');
		QuixHelper::cachecleaner('libquix', 1);
	}

	public static function cachecleaner($group = 'com_quix', $client_id = 0){
		$conf = \JFactory::getConfig();

		$options = array(
			'defaultgroup' => $group,
			'cachebase' => $client_id ? JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache'),
			'result' => true,
		);

		try
		{
			/** @var \JCacheControllerCallback $cache */
			$cache = \JCache::getInstance('callback', $options);
			$cache->clean();
		}
		catch (\JCacheException $exception)
		{
			$options['result'] = false;
		}

		// Trigger the onContentCleanCache event.
		\JEventDispatcher::getInstance()->trigger('onContentCleanCache', $options);
	}
}
