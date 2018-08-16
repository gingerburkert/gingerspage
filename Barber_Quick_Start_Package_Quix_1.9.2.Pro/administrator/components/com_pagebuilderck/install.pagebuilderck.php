<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
defined('_JEXEC') or die('Restricted access');
/*
	preflight which is executed before install and update
	install
	update
	uninstall
	postflight which is executed after install and update
	*/

class com_pagebuilderckInstallerScript {

	function install($parent) {
		
	}
	
	function update($parent) {
		
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element = "com_pagebuilderck"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}

	/*
	* update the table
	*/
	function updateTable($version) {
		$sqlsrc = dirname(__FILE__).'/administrator/sql/updates/' . $version . '.sql';
		$query = file_get_contents($sqlsrc);
		$db = JFactory::getDbo();
		$db->setQuery($query);
		if (!$db->query()) {
			echo '<p class="alert alert-danger">Error during table update for version ' . $version . '</p>';
		} else {
			echo '<p class="alert alert-success">Table successfully updated for version ' . $version . '</p>';
		}
	}

	function uninstall($parent) {
		// jimport('joomla.installer.installer');
		// Latest Module
		// Check first that the module exist
		/*$db->setQuery('SELECT `extension_id` FROM #__extensions WHERE `element` = "mod_test" AND `type` = "module"');
		$id = $db->loadResult();
		if($id)
		{
			$installer = new JInstaller;
			$result = $installer->uninstall('module',$id,1);
		}*/

		// disable all plugins and modules
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%pagebuilderck%'");
		$db->query();

		$db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%pagebuilderck%' AND `folder` NOT LIKE '%pagebuilderck%'");
		$db->query();
		return true;
	}

	function preflight($type, $parent) {
		$db = JFactory::getDbo();
		$tablesList = $db->getTableList();
		// test if the table not exists
		$tableExists = in_array($db->getPrefix() . 'pagebuilderck_elements', $tablesList);
		if (! $tableExists) {
			$this->updateTable('2.2.0');
		}

		return true;
	}

	// run on install and update
	function postflight($type, $parent) {
		// install modules and plugins
		jimport('joomla.installer.installer');
		$db = JFactory::getDbo();
		$status = array();
		$src_ext = dirname(__FILE__).'/administrator/extensions';
		$installer = new JInstaller;

		// extensions to install
		// system plugin
		$result = $installer->install($src_ext.'/system_pagebuilderck');
		$status[] = array('name'=>'System - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// system plugin must be enabled for user group limits and private areas
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuilderck' AND `type` = 'plugin'");
		$db->query();

		// editor button plugin
		$result = $installer->install($src_ext.'/pagebuilderckbutton');
		$status[] = array('name'=>'Button - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// auto enable the plugin
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuilderckbutton' AND `type` = 'plugin'");
		$db->query();

		// editor plugin (editor button type)
		$result = $installer->install($src_ext.'/pagebuilderckeditor');
		$status[] = array('name'=>'Editor - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// auto enable the plugin
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuilderckeditor' AND `type` = 'plugin'");
		$db->query();

		// search plugin
		$result = $installer->install($src_ext.'/pagebuildercksearch');
		$status[] = array('name'=>'Search - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// auto enable the plugin
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuildercksearch' AND `type` = 'plugin'");
		$db->query();

		// module
		$result = $installer->install($src_ext.'/mod_pagebuilderck');
		$status[] = array('name'=>'Page Builder CK - Module','type'=>'module', 'result'=>$result);
		// auto enable the plugin
		// $db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'mod_pagebuilderck' AND `type` = 'module'");
		// $db->query();

		// pagebuilderck plugin
		$plugins = array('text', 'icon', 'icontext', 'image', 'separator', 'message', 'tabs', 'accordion', 'module', 'video', 'audio');
		$ordering = 1;
		foreach ($plugins as $plugin) {
			$result = $installer->install($src_ext . '/' . $plugin);
			$status[] = array('name'=>'Pagebuilder CK - ' . $plugin,'type'=>'plugin', 'result'=>$result);
			// auto enable the plugin
			$db->setQuery("UPDATE #__extensions SET enabled = '1', ordering = '" . $ordering . "' WHERE `element` = '" . $plugin . "' AND `type` = 'plugin' AND `folder` = 'pagebuilderck'");
			$db->query();
			$ordering++;
		}

		foreach ($status as $statu) {
			if ($statu['result'] == true) {
				$alert = 'success';
				$icon = 'icon-ok';
				$text = 'Successful';
			} else {
				$alert = 'warning';
				$icon = 'icon-cancel';
				$text = 'Failed';
			}
			echo '<div class="alert alert-' . $alert . '"><i class="icon ' . $icon . '"></i>Installation and activation of the <b>' . $statu['type'] . ' ' . $statu['name'] . '</b> : ' . $text . '</div>';
		}

		return true;
	}
}
