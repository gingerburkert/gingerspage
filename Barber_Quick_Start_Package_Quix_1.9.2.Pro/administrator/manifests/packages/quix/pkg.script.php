<?php
/**
 * @package		Quix
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @package     Joomla.Administrator
 * @subpackage  com_digicom
 * @since       3.4
 */
class pkg_QuixInstallerScript
{
	public $migration = false;
	
	function preflight( $type, $parent ) {
		
		// Installing component manifest file version
		if($type == 'install')
		{
			$version = $this->getParam('version', 'com_quicx');
			if($version){
				// we found old quix, so check if its less then 
				if( version_compare( $version, '1.0.0', 'lt' ) ) {
					$this->migration = true;
					// we need to migrate the db, uninstall the old extensions
					//first migrate the db
					$this->renameDB();
					
					echo "<p class=\"alert alert-warning\"><strong>Heads Up!</strong><br/>
					We re-branded 'Quicx > Quix' and all your data has been migrated to new tables. Don't panic, your data is completly safe and un-touched. In case of any problem, contact us immediately.</p>";
				}
			}
		}
		else{
			// clean quix cache
			require_once JPATH_ADMINISTRATOR . '/components/com_quix/helpers/quix.php';
			QuixHelper::cleanCache();
		}
	}

	/**
	 * method to rename old tables to new name
	 *
	 * @return void
	 */
	function renameDB()
	{
		$app = JFactory::getApplication(); 
		$prefix = $app->get('dbprefix');

		$db = JFactory::getDbo();
		$tables = JFactory::getDbo()->getTableList();
		
		if(in_array( $prefix.'quicx', $tables)){
			$db->setQuery('RENAME TABLE #__quicx TO #__quix');
			$db->execute();
		}

		if(in_array( $prefix.'quicx_collections', $tables)){
			$db->setQuery('RENAME TABLE #__quicx_collections TO #__quix_collections');
			$db->execute();
		}
		
		if(in_array( $prefix.'quicx_collection_map', $tables)){
			$db->setQuery('RENAME TABLE #__quicx_collection_map TO #__quix_collection_map');
			$db->execute();
		}
		
		return true;		
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name , $options = 'com_quix') {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "'.$options.'"');
		$result = $db->loadResult();
		if(isset($result) && !empty($result)){
			$manifest = json_decode( $result, true );

			return $manifest[ $name ];
		}

		return false;
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function uninstallOldExtensions() 
	{
		JModelLegacy::addIncludePath(JPATH_SITE . '/adminstrator/components/com_installer/models', 'InstallerModel');
		$model = JModelLegacy::getInstance('Manage', 'InstallerModel');
		$db = JFactory::getDbo();
		$db->setQuery("SELECT * FROM `#__extensions` WHERE `name` LIKE '%quicx%'");
		$results = $db->loadObjectList();
		if(isset($results) && !empty($results)){
			// print_r($results);die;
			$ids = array();
			foreach ($results as $key => $value) {
				$ids[] = $value->extension_id;
			}

			JArrayHelper::toInteger($ids, array());
			$model->remove($ids);
		}

		return true;
	}

	/*
	* update db structure
	*/
	function updateDBfromOLD()
	{
		$db = JFactory::getDbo();
		$query = "SHOW COLUMNS FROM `#__quix` LIKE 'catid'";
		$db->setQuery($query);
		$column = $db->loadObject();
		if(!COUNT($column)){
			$query = "
				ALTER TABLE  `#__quix` 
				ADD `catid` int(11) NOT NULL AFTER  `title`,
				ADD `version` int(10) unsigned NOT NULL DEFAULT '1' AFTER `params`,
				ADD `hits` int(11) NOT NULL AFTER `version`,
				ADD `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.' AFTER `hits`,
				ADD INDEX `idx_access` (`access`),
				ADD INDEX `idx_catid` (`catid`),
				ADD INDEX `idx_state` (`state`),
				ADD INDEX `idx_createdby` (`created_by`),
				ADD INDEX `idx_xreference` (`xreference`);
				";
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Function to perform changes during install
	 *
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function postflight($parent)
	{
		self::enablePlugins();
		self::insertMissingUcmRecords();
		
		if($this->migration)
		{
			// now uninstall all the extensions
			$this->uninstallOldExtensions();
		}

		$this->updateDBfromOLD();

		ob_start();	
		?>
			<div class="quix_success_message">
		      	<style>
				    .quix-wrap{
				      background: #5d3ed2;
				      color: #fff;
				      padding: 20px;
				      border-radius: 4px;
				      box-shadow: 0 0 4px #ddd;
				    }
				    .quix-wrap img{ margin-right: 40px; }
				    .quix-wrap .btn-link{ 
				      background: #e91e63; color: #fff; display: inline-block;
				      padding: 0 2rem; margin-right: 10px; margin-top: 15px; height: 36px; line-height: 36px;
				      text-align: center; letter-spacing: .5px; text-transform: uppercase; text-decoration: none; 
				      transition: .2s ease-out; border-radius: 2px;
				      box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
				    }
				    .quix-wrap .btn-link:hover{ background: #ec407a;}
		    	</style>
			    <div class="media quix-wrap">
					<div class="pull-left">
						<img width="170" height="195" title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAM0AAADDCAYAAAAhtpUZAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0RDNEZDMjhDM0YxMTFFNUI1RUFFNUNBMkJFRjhDQTQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0RDNEZDMjlDM0YxMTFFNUI1RUFFNUNBMkJFRjhDQTQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3REM0RkMyNkMzRjExMUU1QjVFQUU1Q0EyQkVGOENBNCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3REM0RkMyN0MzRjExMUU1QjVFQUU1Q0EyQkVGOENBNCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PhQ1FiIAACKrSURBVHja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" />

					</div>
					<div class="media-body">
						<h3>Quix Installed Successfully!</h3>
						<p>Quix is the world’s first truly responsive drag-and-drop page builder for Joomla! Using Quix, you can have your entire Joomla! website up & running in under 10 minutes.</p>
						<p>
							<a class="btn-link" href="index.php?option=com_quix">Get Started</a>
						</p>
					</div>
			    </div>  
    		</div>
    
		<?php
	}
	
	/**
	* enable necessary plugins to avoid bad experience
	*/
	function enablePlugins()
	{
		$db = JFactory::getDBO();
		$sql = "SELECT `element`,`folder` from `#__extensions` WHERE `type` = 'plugin' AND `folder` in ('finder', 'system', 'content', 'editors-xtd') AND `name` like '%quix%' AND `enabled` = '0'";
		$db->setQuery($sql);
		$plugins = $db->loadObjectList();
		if(count($plugins)){
			foreach ($plugins as $key => $value) {
				if($value->folder == 'finder' or $value->folder == 'system' or $value->folder == 'editors-xtd')
				{
			    	$query = $db->getQuery(true);
			    	$query->update($db->quoteName('#__extensions'));
			    	$query->set($db->quoteName('enabled') . ' = '.$db->quote('1'));
			    	$query->where($db->quoteName('type') . ' = '.$db->quote('plugin'));
			    	$query->where($db->quoteName('element') . ' = '.$db->quote($value->element));
			    	$query->where($db->quoteName('folder') . ' = '.$db->quote($value->folder));
		        	$db->setQuery($query);
		        	$db->execute();
				}
				
			}
		}
		
		$sql = "SELECT `element`,`folder`, `enabled` from `#__extensions` WHERE `type` = 'plugin' AND `folder` ='system' AND `element` = 'seositeattributes' AND `enabled` = '0'";
		$db->setQuery($sql);
		$plugins = $db->loadObjectList();
		if(!count($plugins)) return false;
		foreach ($plugins as $key => $value) {

	    	$query = $db->getQuery(true);
	    	$query->update($db->quoteName('#__extensions'));
	    	$query->set($db->quoteName('enabled') . ' = '.$db->quote('1'));
	    	$query->where($db->quoteName('type') . ' = '.$db->quote('plugin'));
	    	$query->where($db->quoteName('element') . ' = '.$db->quote($value->element));
	    	$query->where($db->quoteName('folder') . ' = '.$db->quote($value->folder));
        	$db->setQuery($query);
        	$db->execute();
			
		}

		return true;

	}

	/**
	 * Method to insert missing records for the UCM tables
	 *
	 * @return  void
	 *
	 * @since   3.4.1
	 */
	private function insertMissingUcmRecords()
	{
		// Insert the rows in the #__content_types table if they don't exist already
		$db = JFactory::getDbo();

		// Get the type ID for a xDoc
		$query = $db->getQuery(true);
		$query->select($db->quoteName('type_id'))
			->from($db->quoteName('#__content_types'))
			->where($db->quoteName('type_alias') . ' = ' . $db->quote('com_quix.page'));
		$db->setQuery($query);

		$docTypeId = $db->loadResult();

		// Set the table columns to insert table to
		$columnsArray = array(
			$db->quoteName('type_title'),
			$db->quoteName('type_alias'),
			$db->quoteName('table'),
			$db->quoteName('rules'),
			$db->quoteName('field_mappings'),
			$db->quoteName('router'),
			$db->quoteName('content_history_options'),
		);

		// If we have no type id for com_xdocs.doc insert it
		if (!$docTypeId)
		{
			// Insert the data.
			$query->clear();
			$query->insert($db->quoteName('#__content_types'));
			$query->columns($columnsArray);
			$query->values(
				$db->quote('Quix Page') . ', '
				. $db->quote('com_quix.page') . ', '
				. $db->quote('{"special":{"dbtable":"#__quix","key":"id","type":"Page","prefix":"QuixTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}') . ', '
				. $db->quote('') . ', '
				. $db->quote('{"common":{"core_content_item_id":"id","core_title":"title","core_state":"state","core_body":"description", "core_hits":"hits","core_access":"access", "core_params":"params", "core_metadata":"metadata", "core_language":"language", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_xreference":"xreference", "asset_id":"null"}, "special":{}}') . ', '
				. $db->quote('QuixFrontendHelperRoute::getPageRoute') . ', '
				. $db->quote('{"formFile":"administrator\\/components\\/com_quix\\/models\\/forms\\/page.xml", "hideFields":["asset_id","checked_out","checked_out_time"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits"], "convertToInt":["publish_up", "publish_down", "featured", "ordering"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}')
			);

			$db->setQuery($query);
			$db->execute();
		}

		return true;
	}

}
