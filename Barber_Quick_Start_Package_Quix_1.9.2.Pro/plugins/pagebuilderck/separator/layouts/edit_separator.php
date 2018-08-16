<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
?>
<div id="elementscontainer">
	<div class="menulink" tab="tab_edition"><?php echo JText::_('CK_TEXT'); ?></div>
	<div class="tab menustyles ckproperty ckoption" id="tab_edition">
		<textarea id="<?php echo $id; ?>_text" onchange="ckUpdatePreviewArea()" style=""></textarea>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_ICON'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createTextStyles('separator', 'separatorck', '.editfocus .separatorck i.fa', true) ?>
	</div>
</div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	$ck('#<?php echo $id; ?>_text').val(focus.find('.separatorck_text').html());
	ckUpdatePreviewArea();
	ckFillEditionPopup(focus.attr('id'));
	ckGetIconSize('.editfocus .separatorck i.fa', '#separatoricon-size button');
	ckGetIconPosition('.editfocus .separatorck i.fa', '#separatoricon-position button');
	ckGetIconMargin('.editfocus .separatorck i.fa', '#separatoricon_margin');
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
	focus.find('.separatorck').html($ck('#previewareabloc .separatorck').html());
	focus.find('.ckstyle').html($ck('#previewareabloc .ckstyle').html());
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckUpdatePreviewArea() {
	$ck('.editfocus .separatorck_text').html($ck('#<?php echo $id; ?>_text').val());
}

function ckSelectFaIcon(iconclass) {
	$ck('.editfocus .separatorck').find('i.fa').remove();
	$ck('.editfocus .separatorck_text').before('<i class="' + iconclass + '"></i>');
	$ck('.editfocus .separatorck i.fa').css('vertical-align', $ck('#separatoricon-position button.active').attr('data-position'))
		.addClass($ck('#separatoricon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .separatorck i.fa', '#separatoricon_margin');
}

ckInitIconSize('.editfocus .separatorck i.fa', '#separatoricon-size button');
ckInitIconPosition('.editfocus .separatorck i.fa', '#separatoricon-position button');
</script>