<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}

$doc = JFactory::getDocument();
// JHtml::_('jquery.framework', true); // to avoid because it renders an issue with the template in edition mode
//JHtml::_('bootstrap.framework');
$editor = JFactory::getConfig()->get('pagebuilderck_replaced_editor', '') ? JFactory::getConfig()->get('pagebuilderck_replaced_editor') : JFactory::getConfig()->get('editor');
$editor = $editor == 'jce' ? 'jce' : 'tinymce';
?>
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/pagebuilderck.css" type="text/css" />
<?php // needs also to load the frontend styles to make the same visual as on frontend ?>
<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/pagebuilderck.css" type="text/css" />
<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/colpick.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckbox.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
<?php //JHtmlBootstrap::loadCss($includeMaincss = false, $doc->direction); ?>
<?php JHtml::_('behavior.core'); ?>
<script type="text/javascript">
	var URIROOT = "<?php echo JUri::root(true); ?>";
	var URIBASE = "<?php echo JUri::base(true); ?>";
	var CLIPBOARDCK = '';
	var CLIPBOARDCOLORCK = '';
	var BLOCCKSTYLESBACKUP = '';
	var FAVORITELOCKED = '';
	var JoomlaCK = {};
	var PAGEBUILDERCK_MEDIA_URI = '<?php echo PAGEBUILDERCK_MEDIA_URI ?>';
	var PAGEBUILDERCK_ADMIN_URL = '<?php echo PAGEBUILDERCK_ADMIN_URL ?>';
	var PAGEBUILDERCK_TOKEN = cktoken = '<?php echo JFactory::getSession()->getFormToken() ?>';
	var PAGEBUILDERCK_EDITOR = '<?php echo $editor ?>';
</script>
<?php $doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js'); ?>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jquery-uick-custom.js" type="text/javascript"></script>
<script src="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/jquery-uick.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/colpick.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckbox.js" type="text/javascript"></script>
<?php 
switch ($editor) {
	case 'jce':
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/jce.js" type="text/javascript"></script><?php
		break;
	case 'tinymce':
	default:
		if (version_compare(JVERSION, '3.7') >= 0) { // check if we are in Joomla 3.7
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/tinymce2.js" type="text/javascript"></script><?php
		} else { // we are still in an old version
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/tinymce1.js" type="text/javascript"></script><?php
		}
		break;
}

?>
<script type="text/javascript">
	JoomlaCK.submitbutton = Joomla.submitbutton;
	Joomla.submitbutton = function(task) {
		var form = document.getElementById("module-form") 
			|| document.getElementById("modules-form") 
			|| document.getElementById("item-form") 
			|| document.getElementById("adminForm");
		if (task == 'page.restore') {
			ckCallRestorePopup();
		} else {
			if (task != 'page.cancel' && form.title.value == '') {
				form.title.className += ' invalid';
				alert('<?php echo JText::_('CK_TITLE_EMPTY') ?>');
				return;
			}
			if (task != 'page.cancel') {
				var workspace = $ck('#workspaceck');
				// delete all unwanted interface elements in the final code
				ckCleanInterfaceBeforeSave(workspace);
				// replace the base path to keep images during website migration
				if (URIROOT != '' && URIROOT != '/') {
					var replaceUriroot = new RegExp('src="' + URIROOT, "g");
					workspace.html(workspace.html().replace(replaceUriroot, 'src="|URIROOT|'));
				}

				if ($ck('#htmlcode').length) $ck('#htmlcode').attr('value', workspace.html());
			}
			// Joomla.submitform(task);
			
			// check if the function exists, loads it
			if (typeof JoomlaCK.beforesubmitbutton == 'function') { JoomlaCK.beforesubmitbutton(); }
			JoomlaCK.submitbutton(task);
		}
	}

	function ckKeepAlive() {
		jQuery.ajax({type: "POST", url: "index.php"});
	}

	<?php if (! PagebuilderckHelper::getParams()) { ?>
	function ckShowFavoritePopup() {
		CKBox.open({handler:'inline',content: 'pagebuilderckparamsmessage', fullscreen: false, size: {x: '600px', y: '150px'}});
	}
	function ckShowLibraryPopup() {
		CKBox.open({handler:'inline',content: 'pagebuilderckparamsmessage', fullscreen: false, size: {x: '600px', y: '150px'}});
	}
	<?php } ?>

	jQuery(document).ready(function()
	{
		jQuery('.hasTooltip').tooltip({"container": false});
		window.setInterval("ckKeepAlive()", 600000);
	});

	(function() {
		var strings = {"CK_CONFIRM_DELETE": "<?php echo JText::_('CK_CONFIRM_DELETE') ?>", 
			"CK_FAILED_SET_TYPE": "<?php echo JText::_('CK_FAILED_SET_TYPE') ?>",
			// "TEMPLATE_MUST_HAVE_WIDTH": "<?php echo JText::_('TEMPLATE_MUST_HAVE_WIDTH') ?>",
			"CK_FAILED_SAVE_ITEM_ERRORMENUTYPE": "<?php echo JText::_('CK_FAILED_SAVE_ITEM_ERRORMENUTYPE') ?>",
			"CK_ALIAS_EXISTS_CHOOSE_ANOTHER": "<?php echo JText::_('CK_ALIAS_EXISTS_CHOOSE_ANOTHER') ?>",
			"CK_FAILED_SAVE_ITEM_ERROR500": "<?php echo JText::_('CK_FAILED_SAVE_ITEM_ERROR500') ?>",
			"CK_FAILED_SAVE_ITEM": "<?php echo JText::_('CK_FAILED_SAVE_ITEM') ?>",
			"CK_FAILED_TRASH_ITEM": "<?php echo JText::_('CK_FAILED_TRASH_ITEM') ?>",
			"CK_FAILED_CREATE_ITEM": "<?php echo JText::_('CK_FAILED_CREATE_ITEM') ?>",
			"CK_UNABLE_UNPUBLISH_HOME": "<?php echo JText::_('CK_UNABLE_UNPUBLISH_HOME') ?>",
			"CK_TITLE_NOT_UPDATED": "<?php echo JText::_('CK_TITLE_NOT_UPDATED') ?>",
			"CK_LEVEL_NOT_UPDATED": "<?php echo JText::_('CK_LEVEL_NOT_UPDATED') ?>",
			"CK_SAVE_LEVEL_FAILED": "<?php echo JText::_('CK_SAVE_LEVEL_FAILED') ?>",
			"CK_SAVE_ORDER_FAILED": "<?php echo JText::_('CK_SAVE_ORDER_FAILED') ?>",
			"CK_CHECKIN_NOT_UPDATED": "<?php echo JText::_('CK_CHECKIN_NOT_UPDATED') ?>",
			"CK_CHECKIN_FAILED": "<?php echo JText::_('CK_CHECKIN_FAILED') ?>",
			"CK_PARAM_NOT_UPDATED": "<?php echo JText::_('CK_PARAM_NOT_UPDATED') ?>",
			"CK_PARAM_UPDATE_FAILED": "<?php echo JText::_('CK_PARAM_UPDATE_FAILED') ?>",
			"CK_FIRST_CREATE_ROW": "<?php echo JText::_('CK_FIRST_CREATE_ROW') ?>",
			"CK_EDIT": "<?php echo JText::_('CK_EDIT') ?>",
			"CK_ICON": "<?php echo JText::_('CK_ICON') ?>",
			"CK_MODULE": "<?php echo JText::_('CK_MODULE') ?>",
			"CK_GOOGLE_FONT": "<?php echo JText::_('CK_GOOGLE_FONT') ?>",
			"CK_FULLSCREEN": "<?php echo JText::_('CK_FULLSCREEN') ?>",
			"CK_RESTORE": "<?php echo JText::_('CK_RESTORE') ?>",
			"CK_REMOVE_BLOCK": "<?php echo JText::_('CK_REMOVE_BLOCK') ?>",
			"CK_MOVE_BLOCK": "<?php echo JText::_('CK_MOVE_BLOCK') ?>",
			"CK_EDIT_STYLES": "<?php echo JText::_('CK_EDIT_STYLES') ?>",
			"CK_DECREASE_WIDTH": "<?php echo JText::_('CK_DECREASE_WIDTH') ?>",
			"CK_INCREASE_WIDTH": "<?php echo JText::_('CK_INCREASE_WIDTH') ?>",
			"CK_ADD_BLOCK": "<?php echo JText::_('CK_ADD_BLOCK') ?>",
			"CK_REMOVE_ROW": "<?php echo JText::_('CK_REMOVE_ROW') ?>",
			"CK_EDIT_COLUMNS": "<?php echo JText::_('CK_EDIT_COLUMNS') ?>",
			"CK_MOVE_ROW": "<?php echo JText::_('CK_MOVE_ROW') ?>",
			"CK_ADD_NEW_ROW": "<?php echo JText::_('CK_ADD_NEW_ROW') ?>",
			"CK_REMOVE_ITEM": "<?php echo JText::_('CK_REMOVE_ITEM') ?>",
			"CK_MOVE_ITEM": "<?php echo JText::_('CK_MOVE_ITEM') ?>",
			"CK_DUPLICATE_ITEM": "<?php echo JText::_('CK_DUPLICATE_ITEM') ?>",
			"CK_DUPLICATE_ROW": "<?php echo JText::_('CK_DUPLICATE_ROW') ?>",
			"CK_EDIT_ITEM": "<?php echo JText::_('CK_EDIT_ITEM') ?>",
			"CK_ADD_COLUMN": "<?php echo JText::_('CK_ADD_COLUMN') ?>",
			"CK_DELETE": "<?php echo JText::_('CK_DELETE') ?>",
			"CK_SAVE_CLOSE": "<?php echo JText::_('CK_SAVE_CLOSE') ?>",
			"CK_DESIGN_SUGGESTIONS": "<?php echo JText::_('CK_DESIGN_SUGGESTIONS') ?>",
			"CK_MORE_MENU_ELEMENTS": "<?php echo JText::_('CK_MORE_MENU_ELEMENTS') ?>",
			"CK_FULLWIDTH": "<?php echo JText::_('CK_FULLWIDTH') ?>",
			"CK_DUPLICATE_COLUMN": "<?php echo JText::_('CK_DUPLICATE_COLUMN') ?>",
			"CK_ENTER_CLASSNAMES": "<?php echo JText::_('CK_ENTER_CLASSNAMES') ?>",
			"CHECK_IDS_ALERT_PROBLEM": "<?php echo JText::_('CHECK_IDS_ALERT_PROBLEM') ?>",
			"CHECK_IDS_ALERT_OK": "<?php echo JText::_('CHECK_IDS_ALERT_OK') ?>",
			"CK_ENTER_UNIQUE_ID": "<?php echo JText::_('CK_ENTER_UNIQUE_ID') ?>",
			"CK_INVALID_ID": "<?php echo JText::_('CK_INVALID_ID') ?>",
			"CK_ENTER_VALID_ID": "<?php echo JText::_('CK_ENTER_VALID_ID') ?>",
			"CK_CONFIRM_BEFORE_CLOSE_EDITION_POPUP": "<?php echo JText::_('CK_CONFIRM_BEFORE_CLOSE_EDITION_POPUP') ?>",
			"CK_SUGGESTIONS": "<?php echo JText::_('CK_SUGGESTIONS') ?>",
			"CK_RESPONSIVE_SETTINGS_ALIGNED": "<?php echo JText::_('CK_RESPONSIVE_SETTINGS_ALIGNED') ?>",
			"CK_RESPONSIVE_SETTINGS_STACKED": "<?php echo JText::_('CK_RESPONSIVE_SETTINGS_STACKED') ?>",
			"CK_RESPONSIVE_SETTINGS_HIDDEN": "<?php echo JText::_('CK_RESPONSIVE_SETTINGS_HIDDEN') ?>",
			"CK_SAVE": "<?php echo JText::_('CK_SAVE') ?>",
			"CK_FIRST_CLEAR_VALUE": "<?php echo JText::_('CK_FIRST_CLEAR_VALUE') ?>"};
		if (typeof Joomla == 'undefined') {
			Joomla = {};
			Joomla.JText = strings;
		}
		else {
			Joomla.JText.load(strings);
		}
	})();
</script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/pagebuilderck.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckbrowse.js" type="text/javascript"></script>