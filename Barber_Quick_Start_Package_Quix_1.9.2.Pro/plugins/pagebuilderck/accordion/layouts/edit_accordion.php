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
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_ACCORDION_EDITION'); ?></div>
	<div class="tab menustyles ckproperty tab_fullscreen" id="tab_blocstyles">
		<?php // echo PagebuilderckHelper::renderEditionButtons(); ?>
		<div id="items_edition_list">
		</div>
		<div onclick="ckAddNewListItem()" class="item_add btn btn-small btn-info"><?php echo JText::_('CK_ADD_ITEM'); ?></div>
		<div class="clr"></div>
	</div>
	<div class="menulink" tab="tab_headingaccordionstyles"><?php echo JText::_('CK_ACCORDION_HEADING_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_headingaccordionstyles">
		<?php echo $this->menustyles->createBlocStyles('headingaccordion', 'accordionsck', false, false) ?>
	</div>
	<div class="menulink" tab="tab_activeheadingaccordionstyles"><?php echo JText::_('CK_ACCORDION_ACTIVE_HEADING_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_activeheadingaccordionstyles">
		<?php echo $this->menustyles->createBlocStyles('activeheadingaccordion', 'accordionsck', false, false) ?>
	</div>
	<div class="menulink" tab="tab_contentaccordionstyles"><?php echo JText::_('CK_ACCORDION_CONTENT_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_contentaccordionstyles">
		<?php echo $this->menustyles->createBlocStyles('contentaccordion', 'accordionsck', false, false) ?>
	</div>
	<div class="clr"></div>
</div>
<div class="clr"></div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	var noactivetabscss = focus.find('.accordionsck').attr('activetab') === 'false' ? ' btn-warning active' : '';
	$ck('#items_edition_list').append('<div><div class="item_setdefault btn-small btn'+noactivetabscss+'" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span><?php echo JText::_('CK_SET_DEFAULT_CLOSED', true) ?></div></div>');
	$ck('.accordionsck .itemtitleck', focus).each(function(i, el) {
		var itemedition = ckCreateEditItem(i, $ck('#items_edition_list'), $ck(el).text(), ckContentToEditor($ck('.accordionsck .itemcontentck', focus).eq(i).html()));
		ckMakeEditItemAccordion(itemedition);
		ckLoadEditorOnTheFly('item_content_' + i);
	});
	$ck('.item_setdefault').eq((parseInt(focus.find('.accordionsck').attr('activetab'))+1)).addClass('btn-warning').addClass('active');
	ckMakeEditItemsSortable();
	ckFillEditionPopup(focus.attr('id'));
}

function ckMakeEditItemAccordion(el) {
	$ck(el).accordionck({
		header: ".item_toggler",
		collapsible: true,
		active: false,
		heightStyle: "content"
	});
}

function ckAddNewListItem() {
	var focus = $ck('.editfocus');
	// add the element in the accordion
	$ck('.accordionsck', focus).append(ckGetNewAccordionItem('Lorem Ipsum ...', '<p>Lorem Ipsum ...</p>'));
	$ck('.accordionsck', focus).accordionck( "refresh" );
	// add the element for edition
	var index = $ck('.accordionsck > .ui-accordion-header', focus).length;
	var itemedition = ckCreateEditItem(index, $ck('#items_edition_list'), 'Lorem Ipsum ...', '<p>Lorem Ipsum ...</p>');
	ckMakeEditItemAccordion(itemedition);
	ckLoadEditorOnTheFly('item_content_' + index);
}

function ckGetNewAccordionItem(title, content) {
	var html = '<h3><span class="itemtitleck">'+title+'</span></h3>'
		+'<div class="accordionck itemcontentck">'
			+content
		+'</div>';

	return html;
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');

	$ck('.item_content_edition').each(function() {
		var textID = $ck(this).attr('id');
		ckSaveEditorOnTheFly(textID);
//		ckRemoveEditorOnTheFly(textID);
	});
	var focus = $ck('.editfocus');
	$ck('#items_edition_list .item_edition:not(.ui-sortable-helper)').each(function(i, el) {
		$ck('.itemtitleck', focus).eq(i).text($ck(el).find('.item_title_edition').val());
		$ck('.itemcontentck', focus).eq(i).html(ckEditorToContent($ck(el).find('.item_content_edition').val()));
	});
	
	var activetab = false;
	$ck('#popup_editionck .item_edition').each(function(i, el) {
		if ($ck(el).find('.item_setdefault.active').length) {
			activetab = i;
		}
	});
	focus.find('.accordionsck').attr('activetab', activetab).accordionck("refresh");

//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckSaveInlineEditionPopup() {
	ckBeforeSaveEditionPopup();
}

function ckSetDefaultEditItem(item) {
	$ck('.item_setdefault').removeClass('btn-warning').removeClass('active');
	item.find('.item_setdefault').addClass('btn-warning').addClass('active');
}

function ckBeforeCloseEditionPopup() {
	$ck('.item_content_edition').each(function() {
		ckRemoveEditorOnTheFly($ck(this).attr('id'));
	});
}

function ckBeforeDeleteEditItem(item) {
	var focus = $ck('.editfocus');
	var index_item = item.index('.item_edition');
	ckRemoveEditorOnTheFly(item.find('.item_content_edition').attr('id'));
	$ck('.accordionsck > .ui-accordion-header', focus).eq(index_item).find('+ .ui-accordion-content').remove();
	$ck('.accordionsck > .ui-accordion-header', focus).eq(index_item).remove();
	$ck('.accordionsck', focus).accordionck("refresh");
}

function ckUpdatePreviewArea() {
	// var focus = $ck('.editfocus');
	// $ck('#items_edition_list .item_edition:not(.ui-sortable-helper)').each(function(i, el) {
		// $ck('.itemtitleck', focus).eq(i).text($ck(el).find('.item_title_edition').val());
		// $ck('.itemcontentck', focus).eq(i).html($ck(el).find('.item_content_edition').val());
	// });
}

function ckMakeEditItemsSortable() {
	$ck( "#items_edition_list" ).sortable({
		items: ".item_edition",
		helper: "clone",
		// axis: "y",
		handle: "> .item_move",
		forcePlaceholderSize: true,
		tolerance: "pointer",
		placeholder: "placeholderck",
		// zIndex: 9999,
		start: function(e, ui){
			$ck(this).find('.item_content_edition').each(function(){
				if (tinymce.get($ck(this).attr('id'))) {
					ckRemoveEditorOnTheFly($ck(this).attr('id'));
				}
			});
		},
		update: function(e, ui) {
			$ck(this).find('.item_content_edition:not(.ui-sortable-helper)').each(function(){
				ckLoadEditorOnTheFly($ck(this).attr('id'));
			});
			ckUpdatePreviewArea();
			$ck( "#<?php echo $id; ?>_preview_accordion" ).accordionck("refresh");
		}
	});
}

</script>