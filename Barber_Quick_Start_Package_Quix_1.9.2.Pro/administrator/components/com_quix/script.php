<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined( '_JEXEC' ) or die;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @package     Joomla.Administrator
 * @subpackage  com_quix
 * @since       1.3.0
 */
class Com_QuixInstallerScript
{
	/**
	 * Function to perform changes during install
	 *
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function postflight( $type, $parent )
	{
		if ( $type == 'install' ) {
			// code
		}

		self::updateDB();
	}

	/*
	* update db structure
	*/
	function updateDB()
	{
		$db = JFactory::getDbo();
		$query = "SHOW COLUMNS FROM `#__quix` LIKE 'created'";
		$db->setQuery($query);
		$column = $db->loadObject();
		if(!COUNT($column)){
			$query = "ALTER TABLE `#__quix` ";
			$query .= "ADD `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `access`, ";
			$query .= "ADD `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `created_by`, ";
			$query .= "ADD `modified_by` int(10) unsigned NOT NULL DEFAULT '0' AFTER `modified`";
			$db->setQuery($query);
			$db->execute();
		}
		
		$db->clear();
		$query = "SHOW COLUMNS FROM `#__quix_collections` LIKE 'created'";
		$db->setQuery($query);
		$column = $db->loadObject();
		if(!COUNT($column)){
			$query = "ALTER TABLE `#__quix_collections` ";
			$query .= "ADD `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `access`, ";
			$query .= "ADD `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `created_by`, ";
			$query .= "ADD `modified_by` int(10) unsigned NOT NULL DEFAULT '0' AFTER `modified`";
			$db->setQuery($query);
			$db->execute();
		}
	}

}
