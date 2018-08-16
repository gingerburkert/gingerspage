<?php
/**
 * @name		Slider CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// no direct access
defined('_JEXEC') or die;

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}
$imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/';
$input = JFactory::getApplication()->input;
$fieldid = $input->get('fieldid', '', 'string');

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addStylesheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.css');
$doc->addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.js');

?>
<h3><?php echo JText::_('CK_MENU_ITEMS') ?></h3>
<p><?php echo JText::_('CK_MENU_ITEMS_DESC') ?></p>
<div id="ckfoldertreelist">
<?php
foreach ($this->menus as $menu) {
	?>
	<div class="ckfoldertree parent">
		<div class="ckfoldertreetoggler" onclick="ckToggleTreeSub(this, 0)" data-menutype="<?php echo $menu->menutype; ?>"></div>
		<div class="ckfoldertreename"><img src="<?php echo $imagespath ?>folder.png" /><?php echo utf8_encode($menu->title); ?></div>
	</div>
	<?php
}
?>
</div>
<script>
var $ck = window.$ck || jQuery.noConflict();
var URIROOT = window.URIROOT || '<?php echo JUri::root(true) ?>';
var cktoken = '<?php echo JSession::getFormToken() ?>';
//ckMakeTooltip();

function ckToggleTreeSub(btn, parentid) {
	var item = $ck(btn).parent();
	if (item.hasClass('ckopened')) {
		item.removeClass('ckopened');
	} else {
		item.addClass('ckopened');
		// load only the items if not already there
		if (! item.find('.cksubfolder').length) {
			var menutype = $ck(btn).attr('data-menutype');
			ckShowItems(btn, menutype, parentid);
		}
	}
}

function ckShowItems(btn, menutype, parentid) {
	if ($ck(btn).hasClass('empty')) return;
	ckAddWaitIcon(btn);
	var item = $ck(btn).parent();
	// ajax call to code and return items layout
	var myurl = URIROOT + "/administrator/index.php?option=com_pagebuilderck&task=ajaxShowMenuItems&" + cktoken + "=1";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			menutype: menutype,
			parentid: parentid
		}
	}).done(function(code) {
		if (code.trim().length == 0) {
			$ck(btn).css('opacity', 0).addClass('empty');
		} else {
			item.append(code);
			ckInitTooltips();
		}
		ckRemoveWaitIcon(btn);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSetMenuItemUrl(url) {
	window.parent.document.getElementById('<?php echo $fieldid ?>').value = url;
	$ck(window.parent.document.getElementById('<?php echo $fieldid ?>')).trigger('change');
	window.parent.CKBox.close('#ckmenusmodal .ckboxmodal-button');
}
</script>