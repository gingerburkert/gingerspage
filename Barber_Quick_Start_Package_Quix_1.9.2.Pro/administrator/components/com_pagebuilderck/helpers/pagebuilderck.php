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
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}

include_once(JPATH_SITE . '/administrator/components/com_pagebuilderck/helpers/ckeditor.php');

/**
 * Helper Class.
 */
class PagebuilderckHelper {

	private static $pluginsItemType;

	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '') {
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/pagebuilderck.css');
		if (! $vName) $vName = $input->get('view', 'pages');
		JSubMenuHelper::addEntry(
				JText::_('COM_PAGEBUILDERCK_PAGES'), 'index.php?option=com_pagebuilderck&view=pages', $vName == 'pages'
		);
		JSubMenuHelper::addEntry(
				JText::_('COM_PAGEBUILDERCK_ARTICLES'), 'index.php?option=com_pagebuilderck&view=articles', $vName == 'articles'
		);
		JSubMenuHelper::addEntry(
				JText::_('COM_PAGEBUILDERCK_MODULES'), 'index.php?option=com_pagebuilderck&view=modules2', $vName == 'modules2'
		);
		JSubMenuHelper::addEntry(
				JText::_('COM_PAGEBUILDERCK_MY_ELEMENTS'), 'index.php?option=com_pagebuilderck&view=elements', $vName == 'elements'
		);
		JSubMenuHelper::addEntry(
				JText::_('CK_ABOUT') . '<span class="pagebuilderckchecking isbadgeck"></span>', 'index.php?option=com_pagebuilderck&view=about', $vName == 'about'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions() {
		$user = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_pagebuilderck';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/*
	 * Load the default editor
	 * 
	 * Return object the editor instance
	 */
	public static function loadEditor() {
		$conf = JFactory::getConfig();
		// $editorName = $conf->get('editor');
		$editorName = $conf->get('pagebuilderck_replaced_editor') ? $conf->get('pagebuilderck_replaced_editor') : $conf->get('editor');
		$editor = CKEditor::getInstance($editorName);

		// return the instance
		return $editor;
	}

	/*
	 * Check for the plugin params and returns the PHP Class
	 * 
	 * @param string a PHP class to load
	 *
	 * Return mixed the PHP Class, or a message, true or false if no class given in param
	 */
	public static function getParams($class = '') {
		if (file_exists(JPATH_ROOT . '/plugins/system/pagebuilderckparams/pagebuilderckparams.php')
			&& JPluginHelper::isEnabled('system', 'pagebuilderckparams')) {

				if (! $class) return true; // only check if the plugin is installed and active

				// check for the file class and loads it if exists
				if (file_exists(JPATH_ROOT . '/plugins/system/pagebuilderckparams/includes/' . strtolower($class) . '.php')) {
					include_once(JPATH_ROOT . '/plugins/system/pagebuilderckparams/includes/' . strtolower($class) . '.php');
					$newClassName = 'PagebuilderckParams' . ucfirst($class);
					return new $newClassName;
				} else {
					echo '<p class="alert alert-danger">' . JText::_('CK_PAGEBUILDERCK_PARAMS_CLASS_NOT_FOUND') . ' : ' . $class . '</p>';
					return false;
				}
		} else {
			return false;
		}
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCkbox() {
		$doc = JFactory::getDocument();
		// JHtml::_('jquery.framework', true);
		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbox.js');
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCKFramework() {
		$doc = JFactory::getDocument();
		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckframework.css');
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadInlineCKFramework() {
	?>
		<script src="<?php echo JUri::root(true) ?>/media/jui/js/jquery.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/font-awesome.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
	<?php
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadParamsAssets() {
		$doc = JFactory::getDocument();
		// JHtml::_('jquery.framework', true);
		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(JUri::root(true) .'/plugins/system/pagebuilderckparams/assets/pagebuilderckparams.css');
		$doc->addScript(JUri::root(true) .'/plugins/system/pagebuilderckparams/assets/pagebuilderckparams.js');
	}

	/*
	 * Show the message about Page Builder CK Params
	 *
	 * Return string - html code
	 */
	public static function showParamsMessage($show = true) {
	
		if (self::getParams()) return '';
		$html = '<div id="pagebuilderckparamsmessage" style="padding:10px;display:'.($show ? 'block' : 'none').';">
					<div class="alert alert-info">
						' . JText::_('CK_PAGEBUILDERCK_PARAMS_INFO') . '
						<div style="text-align:center;"><a class="btn btn-small btn-inverse" target="_blank" href="http://www.joomlack.fr/en/joomla-extensions/page-builder-ck/page-builder-ck-params"><span class="icon-download"></span>&nbsp;Page Builder CK Params</a></div>
					</div>
				</div>';
		return $html;
	}

	/*
	 * Get the page from its id
	 *
	 * Return Array - The list of pages
	 */
	public static function getPage($id = null) {
		if ($id == null) return;
		// get the page model
		include_once JPATH_ROOT . '/administrator/components/com_pagebuilderck/models/page.php';
		$model	= JModelLegacy::getInstance('Page', 'PagebuilderckModel');
		
		// parse the html code through the model page
		$page = $model->getData((int) $id);

		return $page;
	}

	/**
	 * Get the page html code from its id
	 * 
	 * @return string, the html code
	 */
	public static function ajaxLoadPageHtml() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$page = PagebuilderckHelper::getPage($id);
		if (isset($page->htmlcode)) {
			echo trim($page->htmlcode);
		} else {
			echo 'error';
		}
		exit();
	}

	/*
	 * Take the item and save it into a .pbck file as autoamtic backup
	 * 
	 * @return void
	 */
	public static function makeBackup($item, $subfolder = '') {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$subfolder = $subfolder ? '/' . $subfolder : '';
		$path = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup' . $subfolder;

//		$item = $this->getData();
		// $item->htmlcode = str_replace(JUri::root(true), "|URIROOT|", $item->htmlcode);
		// $exportfiletext = json_encode($item);
		$exportfiletext = self::getExportFile($item);

		// create the folder
		if (! JFolder::exists($path . '/' . $item->id . '_bak/')) {
			JFolder::create($path . '/' . $item->id . '_bak/');
		}

		// check if we have more than 5 existing backups, delete the old one
		if (count(JFolder::files($path . '/' . $item->id . '_bak/')) > 5) {
			self::deleteOldestBackup($path . '/' . $item->id . '_bak/', $item->id);
		}

		$exportfiledest = $path . '/' . $item->id . '_bak/backup_' . $item->id . '_' . date("d-m-Y-G-i-s") . '.pbck';
		JFile::write($exportfiledest, $exportfiletext);
	}

	/*
	 * Replace the variables to store the file
	 * 
	 * @return string, the json encoded item
	 */
	public static function getExportFile($item) {
		$item->htmlcode = str_replace(JUri::root(true), "|URIROOT|", $item->htmlcode);
		$exportfiletext = json_encode($item);

		return $exportfiletext;
	}

	/*
	 * Remove the oldest backup from the folder
	 * 
	 * @return void
	 */
	private static function deleteOldestBackup($path, $id) {
		$files = JFolder::files($path);

		$files = array_map(function ($v) use ($id) {
			$date = str_replace('backup_' . $id . '_', '', str_replace('.pbck', '', $v));
			$new_d = PagebuilderckHelper::invertDateForSorting($date);

			return $new_d;
		}, $files);
		natsort($files);

		$oldest = reset($files);
		$oldest = PagebuilderckHelper::invertDateForSorting($oldest);
		$oldest = 'backup_' . $id . '_' . $oldest . '.pbck';
		Jfile::delete($path . $oldest);
	}

	public static function invertDateForSorting($date) {
		$new_d = explode('-', $date);
		$d = $new_d[0];
		$Y = $new_d[2];
		$new_d[0] = $Y;
		$new_d[2] = $d;
		return implode('-', $new_d);
	}

	public static function renderEditionButtons() {
		$html = '<span class="ckbutton ckbutton-success" onclick="ckSaveInlineEditionPopup();"><span class="fa fa-save"></span> ' . JText::_('CK_SAVE_CLOSE') . '</span>';
		$html .= '<span class="ckbutton" onclick="ckCancelInlineEditionPopup(this);">' . JText::_('CK_CANCEL') . '</span>';
		return $html;
	}

	public static function getAjaxToken() {
		// check the token for security
		if (! JSession::checkToken('get')) {
			$msg = JText::_('JINVALID_TOKEN');
			echo '{"result": "0", "message": "' . $msg . '"}';
			return false;
		}
		return true;
	}

	public static function getPluginsMenuItemType() {
		if (empty(self::$pluginsItemType)) {
			$standarditems = array('row', 'readmore');
			$i = 0;
			foreach ($standarditems as $standarditem) {
				$standarditems[$i] = new stdClass();
				$standarditems[$i]->type = $standarditem;
				$standarditems[$i]->title = JText::_('COM_PAGEBUILDERCK_CONTENT_' . strtoupper($standarditem));
				$standarditems[$i]->description = JText::_('COM_PAGEBUILDERCK_CONTENT_' . strtoupper($standarditem) . '_DESC');
				$standarditems[$i]->image = PAGEBUILDERCK_MEDIA_URI . '/images/contents/' . $standarditem . '.png';
				$i++;
			}
			// load the custom plugins
			JPluginHelper::importPlugin( 'pagebuilderck' );
			$dispatcher = JEventDispatcher::getInstance();
			$otheritems = $dispatcher->trigger( 'onPagebuilderckAddItemToMenu' );
			$items = array_merge($standarditems, $otheritems);
			// $items = $otheritems;
			self::$pluginsItemType = array();
			if (count($items)) {
				foreach ($items as $item) {
					self::$pluginsItemType[$item->type] = $item;
				}
			}
		}
		return self::$pluginsItemType;
	}
}
