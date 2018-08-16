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
	<div class="menulink" tab="tab_iconstyles"><?php echo JText::_('CK_ICON_EDITION'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_iconstyles">
		<?php echo $this->menustyles->createIcon('icon', '.editfocus .iconck i.fa', false, false, true) ?>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBackground('icon') ?>
		<?php echo $this->menustyles->createDimensions('icon') ?>
		<?php echo $this->menustyles->createDecoration('icon') ?>
		<?php echo $this->menustyles->createShadow('icon') ?>
	</div>
</div>

<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	ckFillEditionPopup(focus.attr('id'));
	ckGetIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
	ckGetIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
	ckGetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
	$ck('#iconicon-class').val(focus.find('.iconck > i').attr('data-iconclass'));
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckUpdatePreviewArea() {

}

function ckSelectFaIcon(iconclass) {
	// $ck('.editfocus .iconck').find('i.fa').remove();
	$ck('#iconicon-class').val(iconclass);
	$ck('.editfocus .iconck').empty().append('<i class="' + iconclass + '" data-iconclass="' + iconclass + '"></i>');
	$ck('.editfocus .iconck i.fa').css('vertical-align', $ck('#iconicon-position button.active').attr('data-position'))
		.addClass($ck('#iconicon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
}

ckInitIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
ckInitIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
</script>