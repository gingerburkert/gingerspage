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
		<div class="ckoption">
			<div class="menupanetitle"><?php echo JText::_('CK_POSITION'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout('top')"><?php echo JText::_('CK_TOP'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout('bottom')"><?php echo JText::_('CK_BOTTOM'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout('left')"><?php echo JText::_('CK_LEFT'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout('right')"><?php echo JText::_('CK_RIGHT'); ?></div>
	</div>
		<?php echo $this->menustyles->createBackground('icon') ?>
		<?php echo $this->menustyles->createDimensions('icon', true, true) ?>
		<?php echo $this->menustyles->createDecoration('icon') ?>
		<?php echo $this->menustyles->createShadow('icon') ?>
		<?php echo $this->menustyles->createCustom('icon') ?>
	</div>
	<div class="menulink" tab="tab_titleedition"><?php echo JText::_('CK_TITLE_EDITION'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_titleedition">
		<div class="menupanetitle"><?php echo JText::_('CK_TITLE_CONTENT'); ?></div>
		<div class="ckoption">
			<input type="text" id="<?php echo $id; ?>_title" onchange="ckUpdatePreviewArea()" style="width: 90%;" />
		</div>
		<div class="menupanetitle"><?php echo JText::_('CK_TITLE_STYLES'); ?></div>
		<?php echo $this->menustyles->createTextStyles('title', 'titleck', false) ?>
	</div>
	<div class="menulink" tab="tab_textedition"><?php echo JText::_('CK_TEXT_EDITION'); ?></div>
	<div class="tab menustyles ckproperty tab_fullscreen" id="tab_textedition">
		<?php // echo PagebuilderckHelper::renderEditionButtons(); ?>
		<textarea id="<?php echo $id; ?>_text"></textarea>
		<div class="menupanetitle"><?php echo JText::_('CK_TEXT_STYLES'); ?></div>
		<?php echo $this->menustyles->createTextStyles('text', 'titleck', false) ?>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_BLOCK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc') ?>
	</div>
</div>

<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	var textID = '<?php echo $id; ?>_text';

	content =  focus.find('.textck').html();
	content = ckContentToEditor(content);
	$ck('#<?php echo $id; ?>_text').val(content);

	$ck('#<?php echo $id; ?>_title').val(focus.find('.titleck').html());
	ckUpdatePreviewArea();

	ckLoadEditorOnTheFly(textID);

	ckFillEditionPopup(focus.attr('id'));
	ckGetIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
	ckGetIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
	ckGetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
	$ck('#iconicon-class').val(focus.find('.iconck > i').attr('data-iconclass'));
}

function ckUpdateLayout(layout) {
	var focus = $ck('.editfocus');
	// for B/C
	if (! focus.find('.contentck').length) {
		focus.find('> .inner').append('<div class="contentck" />');
		var contentck = focus.find('.contentck');
		contentck.append(focus.find('.titleck'));
		contentck.append(focus.find('.textck'));
	}

	focus.attr('data-layout', layout); 
}

/*
 * Method automatically called in ckCloseEditionPopup() if exists
 */
function ckBeforeCloseEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckRemoveEditorOnTheFly(textID);
}

function ckBeforeSaveEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckSaveEditorOnTheFly(textID);
	var content = ckEditorToContent($ck('#' + textID).val());
	// var search = /<img(.*?)src="(.*?)"/g;
	// content = content.replace(search, '<img src="<?php echo JUri::root(true); ?>/$2"');

	var focus = $ck('.editfocus');
	focus.find('.textck').html(content);
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckSaveInlineEditionPopup() {
	ckBeforeSaveEditionPopup();
}

function ckUpdatePreviewArea() {
	var focus = $ck('.editfocus');
	$ck('.titleck', focus).html($ck('#<?php echo $id; ?>_title').val());
}

function ckSelectFaIcon(iconclass) {
	$ck('#iconicon-class').val(iconclass);
	$ck('.editfocus .iconck').empty().append('<i class="' + iconclass + '" data-iconclass="' + iconclass + '"></i>');
	$ck('.editfocus .iconck i.fa').css('vertical-align', $ck('#iconicon-position button.active').attr('data-position'))
		.addClass($ck('#iconicon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
}

ckInitIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
ckInitIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
</script>