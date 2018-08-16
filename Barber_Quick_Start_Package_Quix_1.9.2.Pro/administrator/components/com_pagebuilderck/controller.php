<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */


// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

class PagebuilderckController extends JControllerLegacy
{
	static $releaseNotes;

	static $currentVersion;

	public function display($cachable = false, $urlparams = false)
	{
		// load the views only in backend
		if (JFactory::getApplication()->isAdmin()) {
			$input	= JFactory::getApplication()->input;
			$view	= $input->get('view', 'pages');
			$input->set('view', $view);
			self::loadUpdatecheckJs();
		}

		parent::display();

		return $this;
	}

	function loadUpdatecheckJs() {
		$js_checking = 'jQuery(document).ready(function (){
				jQuery(\'.pagebuilderckchecking\').each(function(i ,el){
					var isbadge = jQuery(el).hasClass(\'isbadgeck\') ? 1 : 0;
					jQuery.ajax({
						type: "POST",
						url: \'' . JUri::root(true) . '/administrator/index.php?option=com_pagebuilderck&task=checkUpdate\',
						data: {
							isbadge : isbadge
						}
					}).done(function(response) {
						response = response.trim();
						if ( response.substring(0,7).toLowerCase() == \'error\' ) {
							// alert(response);
							// show_ckmodal(response);
						} else {
							jQuery(el).append(response);
						}
					}).fail(function() {
						// alert(Joomla.JText._(\'CK_FAILED\', \'Failed\'));
					});
				});
			});';
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js_checking);
	}
	
	/**
	* Check updates for the component, module, or plugins
	*/
	public function checkUpdate() {
		$input = JFactory::getApplication()->input;
		$isBadge = $input->get('isbadge', 0, 'int');
		$latest_version = self::getLatestVersion();
		$update_status = '';
		if (self::isOutdated()) {
			if ($isBadge) {
				$update_status = '<span class="badge-alertck">' . JText::_('CK_UPDATE_NOTIFICATION') . '</span>';
			} else {
				$update_status = '<p class="alert alert-warning">' . JText::_('CK_IS_OUTDATED') . ' : <b>' . $latest_version . '</b></p>';
			}
		} else {
			// $update_status = '<p class="alert alert-success">' . JText::_('CK_IS_UPTODATE') . '</p>';
		}

		echo $update_status;
		exit();
	}

	/**
	 * Check if a new version is available
	 * 
	 * @return false, or the latest version
	 */
	public static function getLatestVersion() {
		$releaseNotes = self::getReleaseNotes();
		$latest_version = false;
		if ($releaseNotes) {
			// $test_version = preg_match('/\*(.*?)\n/', $releaseNotes, $results);
			// $latest_version = trim($results[1]);
			$latest_version = $releaseNotes->version;
		}

		return $latest_version;
	}
	
	/*
	 * Get a variable from the manifest file.
	 * 
	 * @return the current version
	 */
	public static function getCurrentVersion() {
		if (! self::$currentVersion) {
			// get the version installed
			self::$currentVersion = false;
			$file_url = JPATH_SITE .'/administrator/components/com_pagebuilderck/pagebuilderck.xml';
			if (! $xml_installed = JFactory::getXML($file_url)) {
				// die;
			} else {
				self::$currentVersion = (string)$xml_installed->version;
			}
		}

		return self::$currentVersion;
	}

	/**
	 * Get the release notes content
	 * 
	 * @return false or the file content
	 */
	public static function getReleaseNotes() { 
		if (! self::$releaseNotes) {
			// $url = 'http://update.joomlack.fr/pagebuilderck_update.txt';
			$url = 'http://update.joomlack.fr/com_pagebuilderck_notes.json';
			$releaseNotes = @file_get_contents($url);
			self::$releaseNotes = json_decode($releaseNotes);
		}
		
		return self::$releaseNotes;
	}

	/**
	 * Format the release notes in html
	 */
	public static function displayReleaseNotes() {
		$releaseNotes = self::getReleaseNotes();
		if (! isset($releaseNotes->releasenotes)) return;

		if (self::isOutdated()) {
			echo '<br /><p style="text-transform:uppercase;text-decoration: underline;">Release notes :</p><br />';
		}
		foreach ($releaseNotes->releasenotes as $i => $v) {
			// stop at the current version notes
			if (version_compare($i, self::getCurrentVersion() ) <= 0) break;

			echo '<h4>VERSION : ' . $i . ' - ' . $v->date . '</h4>';
			echo '<ul>';
				foreach ($v->notes as $n) {
					echo '<li>' . htmlspecialchars($n) . '</li>';
				}
			echo '</ul>';
		}
	}

	/**
	 * Check if you have the latest version
	 * 
	 * @return boolean, true if outdated
	 */
	public static function isOutdated() {
		return version_compare(self::getLatestVersion(), self::getCurrentVersion() ) > 0;
	}

	/*
	 * Get a variable from the manifest file.
	 * 
	 * @return the current version
	 */
	public static function getCurrentParamsVersion() {
		// get the version installed
		$installed_version = false;
		$file_url = JPATH_SITE .'/administrator/components/com_pagebuilderckparams/pagebuilderckparams.xml';
		if (! $xml_installed = JFactory::getXML($file_url)) {
			// die;
		} else {
			$installed_version = (string)$xml_installed->version;
		}

		return $installed_version;
	}

	/**
	 * Load the backup file
	 * 
	 * @return string, the html content
	 */
	public function ajaxDoRestoration() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$name = $input->get('name','', 'string');
		$isLocked = $input->get('isLocked', 0, 'int');
		$filename = ($isLocked ? 'locked' : 'backup') . '_' . $id . '_' . $name . '.pbck';
		$path = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $id . '_bak';
		$content = JFile::read($path . '/' . $filename);
		$backup = json_decode($content);

		echo str_replace('|URIROOT|', JUri::root(true), $backup->htmlcode);
		exit();
	}

	/**
	 * Load a method from the Page Builder CK Params
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxParamsCall() {
		$input = JFactory::getApplication()->input;
		$class = $input->get('class', '', 'cmd');
		$method = $input->get('method', '', 'cmd');
		$params = $input->get('params', '', 'array');
		if ($paramsClass = PagebuilderckHelper::getParams($class)) {
			echo $paramsClass->$method($params);
		}
		exit();
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxAddPicture() {
		require_once JPATH_SITE . '/administrator/components/com_pagebuilderck/helpers/ckbrowse.php';
		CKBrowse::ajaxAddPicture();
	}

	/**
	 * Switch between lock / unlock state for the backup file
	 * 
	 * @return string, the html content
	 */
	public function ajaxToggleLockBackup() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$isLocked = $input->get('isLocked', 0, 'int');
		$filename = $input->get('filename', '', 'string');
		// locked_38_09-02-2016-11-30-13
		$filename = ($isLocked ? 'locked' : 'backup') . '_' . $id . '_' . $filename . '.pbck';
		$file = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $id . '_bak/' . $filename;
		// $isLocked = stristr($file, 'locked_');
		if ($isLocked) {
			$newFilename = str_replace('locked_', 'backup_', $filename);
		} else {
			$newFilename = str_replace('backup_', 'locked_', $filename);
		}
		$toFile = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $id . '_bak/' . $newFilename;

		if (@JFile::move($file, $toFile)) {
			echo '1';
		} else {
			echo '0';
		}
		exit();
	}

	/**
	 * Get the page html code from it id
	 * 
	 * @return string, the html code
	 */
	public function ajaxLoadPageHtml() {
		PagebuilderckHelper::ajaxLoadPageHtml();
	}

	/**
	 * Get the page html code from its id
	 * 
	 * @return string, the html code
	 */
	public static function ajaxLoadLibraryHtml() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'string');
//		$page = PagebuilderckHelper::getPage($id);
		$url = 'http://media.joomlack.fr/api/pagebuilderck/page/' . $id;

		try {
			$file = file_get_contents($url);
		} catch (Exception $e) {
			echo 'ERROR : ',  $e->getMessage(), "\n";
			exit();
		}
		$file = json_decode($file);
		if (isset($file->htmlcode)) {
			echo trim($file->htmlcode);
		} else {
			echo 'error';
		}
		exit();
	}

	/*
	 * Check that the user can do
	 */
	public function checkAjaxUserEditRight() {
		$user		= JFactory::getUser();
		$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
		if (! $canEdit) {
			echo '{"status": "0", "msg": "' . JText::_('CK_ERROR_USER_NO_AUTH') . '"}';
			exit();
		}
	}

	/*
	 * Save the current styles into a favorite file
	 */
	public function ajaxSaveFavorite() {
		$this->checkAjaxUserEditRight();

		$blocs = $this->input->get('favorite', null, null);
		$id = $this->input->get('id', -1, 'int');
		$path = PAGEBUILDERCK_PARAMS_PATH . '/favorites';

		$error = 0;
		if (is_numeric($id) && (int) $id > -1) {
			$i = (int) $id;
		} else {
			$i = count(JFolder::files($path, '.fck3'));
			$j = 0;
			while (JFile::exists(PAGEBUILDERCK_PARAMS_PATH . '/favorites/favorite'.$i.'.fck3') && $j < 1000) {
				$i++;
				$j++;
			}
			if ($j >= 1000) {
				echo 'ERROR reach loop of 1000 files';
				$error = 1;
			}
		}

		$exportfiledest = PAGEBUILDERCK_PARAMS_PATH . '/favorites/favorite'.$i.'.fck3';
		$exportfiletext = $blocs;

		if (!JFile::write($exportfiledest, $exportfiletext) || $error == 1) {
			$msg = JText::_('CK_ERROR_CREATING_FAVORITEFILE');
			$status = 0;
		} else {
			$msg = $i;
			$status = 1;
		}

		echo '{"status": "' . $status . '", "msg": "' . $msg . '"}';
		// echo $msg;
		exit();
	}

	/*
	 * Load the favorite file
	 */
	public function ajaxLoadFavorite() {
		$name = $this->input->get('name', '', 'string');
		$folder = $this->input->get('folder', '', 'string');

		$path = PAGEBUILDERCK_PARAMS_PATH . '/'.$folder.'/';

		$content = JFile::read($path . $name . '.fck3');
		echo $content;
		exit();
	}

	/*
	 * Remove the favorite file from the folder
	 */
	public function ajaxRemoveFavorite() {
		$this->checkAjaxUserEditRight();

		$name = $this->input->get('name', '', 'string');

		$msg = '';
		if (!JFile::delete(PAGEBUILDERCK_PARAMS_PATH . '/favorites/' . $name . '.fck3')) {
			$msg = JText::_('CK_ERROR_DELETING_FAVORITEFILE');
			$status = 0;
		} else {
			$status = 1;
		}

		echo '{"status": "' . $status . '", "msg": "' . $msg . '"}';
		exit();
	}

	/*
	 * Get the styles from the fields
	 */
	/*public function getStylesCss() {
		$input = JFactory::getApplication()->input;
		$fields = stripslashes( $input->get('fields', '', 'string'));
		$fields = json_decode($fields);
		$prefix = 'bloc';
		$action = 'preview';
		$id = '';
		$direction = 'ltr';

		$css = CssStyles::genCss($cssparams, $prefix, $action, $id, $direction);
		
	}*/

	function ajaxShowMenuItems() {
		// security check
		if (! PagebuilderckHelper::getAjaxToken()) {
			exit();
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$parentId = $input->get('parentid', 0, 'int');
		$menutype = $input->get('menutype', '', 'string');

		$model = $this->getModel('Menus', '', array());
		$items = $model->getChildrenItems($menutype, $parentId);

		$links = array();
		$imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/';
		?>
		<div class="cksubfolder">
		<?php
		foreach ($items as $item) {
			$aliasId = $item->id;
			if ($item->type == 'alias') {
				$itemParams = new JRegistry($item->params);
				$aliasId = $itemParams->get('aliasoptions', 0);
			}
			$Itemid = substr($item->link,-7,7) == 'Itemid=' ? $aliasId : '&Itemid=' . $aliasId;
		?>
			<div class="ckfoldertree parent">
				<div class="ckfoldertreetoggler <?php if ($item->rgt - $item->lft <= 1) { echo 'empty'; } ?>" onclick="ckToggleTreeSub(this, <?php echo $item->id ?>)" data-menutype="<?php echo $item->menutype; ?>"></div>
				<div class="ckfoldertreename hasTip" title="<?php echo $item->link . $Itemid ?>" onclick="ckSetMenuItemUrl('<?php echo $item->link . $Itemid ?>')"><img src="<?php echo $imagespath ?>folder.png" /><?php echo $item->title; ?></div>
			</div>
		<?php
		}
		?>
		</div>
		<?php
		exit;
	}

	public function ajaxSaveElement() {
		// security check
		if (! PagebuilderckHelper::getAjaxToken()) {
			exit();
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$name = $input->get('name', '', 'string');
		$type = $input->get('type', '', 'string');
		$html = $input->get('html', '', 'raw');
		// $html = json_encode($input->get('html', '', 'raw'));

		$model = $this->getModel('Elements', '', array());
		$id = $model->save($name, $type, $html);
		$pluginsType = PagebuilderckHelper::getPluginsMenuItemType();
		$image = $pluginsType[$type]->image;
		$returncode = '<div data-type=\"' . $type . '\" data-id=\"' . $id . '\" class=\"menuitemck ckmyelement\" >'
						. '<div>'
							. '<div class=\"menuitemck_title\">' . $name . '</div>'
						. '</div>'
						. '<img src=\"' . $image . '\" />'
					. '</div>';
		echo '{"status" : "' . ($id == false ? '0' : '1') . '", "code" : "' . $returncode . '"}';
		exit;
	}

	public function ajaxAddElementItem() {
		// security check
		if (! PagebuilderckHelper::getAjaxToken()) {
			exit();
		}
	
		$app = JFactory::getApplication();
		$input = $app->input;
		$id = $input->get('id', '', 'int');

		$model = $this->getModel('Element', '', array());
		$result = $model->getHtml($id);
		echo ($result == false ? 'ERROR' : $result);
		exit;
	}

	public function fixDb() {
		$this->searchTable('elements');
	}

	private function searchTable($tableName) {
		$db = JFactory::getDbo();

		$tablesList = $db->getTableList();
		$tableExists = in_array($db->getPrefix() . 'pagebuilderck_' . $tableName, $tablesList);
		// test if the table not exists

		if (! $tableExists) {
			$query = $this->getSqlQueryElements();
			$db->setQuery($query);
//			// add the SQL field to the main table
//			$query = 'ALTER TABLE `' . $table . '` ADD `' . $name . '` text NOT NULL;';
			if (! $db->execute($query)) {
				echo '<p class="alert alert-danger">Error during table ' . $tableName . ' creation process !</p>';
			} else {
				echo '<p class="alert alert-success">Table ' . $tableName . ' created with success !</p>';
			}
		} 
	}

	private function getSqlQueryElements() {
		$query = "CREATE TABLE IF NOT EXISTS `#__pagebuilderck_elements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `type` varchar(50) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `catid` varchar(255) NOT NULL,
  `htmlcode` longtext NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;";
		return $query;
	}
}