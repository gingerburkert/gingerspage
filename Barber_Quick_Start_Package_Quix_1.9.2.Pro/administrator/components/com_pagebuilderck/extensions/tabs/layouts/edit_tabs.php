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
	<div class="menulink" tab="tab_edition"><?php echo JText::_('CK_EDITION'); ?></div>
	<div class="tab menustyles ckproperty tab_fullscreen" id="tab_edition">
		<?php // echo PagebuilderckHelper::renderEditionButtons(); ?>
		<div id="items_edition_list">
		</div>
		<div onclick="ckAddNewListItem()" class="item_add btn btn-small btn-info"><?php echo JText::_('CK_ADD_NEW_ITEM'); ?></div>
	</div>
	<div class="menulink" tab="tab_headingtabsstyles"><?php echo JText::_('CK_TABS_HEADING_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_headingtabsstyles">
		<?php echo $this->menustyles->createBlocStyles('headingtabs', 'tabsck', false, false) ?>
	</div>
	<div class="menulink" tab="tab_activeheadingtabsstyles"><?php echo JText::_('CK_TABS_ACTIVE_HEADING_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_activeheadingtabsstyles">
		<?php echo $this->menustyles->createBlocStyles('activeheadingtabs', 'tabsck', false, false) ?>
	</div>
	<div class="menulink" tab="tab_contenttabsstyles"><?php echo JText::_('CK_TABS_CONTENT_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_contenttabsstyles">
		<?php echo $this->menustyles->createBlocStyles('contenttabs', 'tabsck', false, false) ?>
	</div>
</div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	$ck('#<?php echo $id; ?>_preview_tabs').tabsck();
	$ck('.editfocus .tabsck .itemtitleck').each(function(i, el) {
		var itemedition = ckCreateEditItem(i, $ck('#items_edition_list'), $ck(el).text(), ckContentToEditor($ck('.editfocus .tabsck .itemcontentck').eq(i).html()));
		ckMakeEditItemAccordion(itemedition);
//		tinymce.execCommand('mceAddEditor', false, 'item_content_' + i);
		ckLoadEditorOnTheFly('item_content_' + i);
	});
	$ck('.item_setdefault').eq(focus.find('.tabsck').attr('activetab')).addClass('btn-warning').addClass('active');
	ckMakeEditItemsSortable();
	ckFillEditionPopup(focus.attr('id'));
}

function ckMakeEditItemAccordion(el) {
	$ck(el).accordion({
		header: ".item_toggler",
		collapsible: true,
		active: false,
		heightStyle: "content"
	});
}

function ckAddNewListItem() {
	// add the element in the tabs
	var index = $ck('.editfocus .tabsck > ol > li').length;
	$ck('.editfocus .tabsck > ol').append(ckGetNewTabItemTitle('Lorem Ipsum ...', index+1));
	$ck('.editfocus .tabsck').append(ckGetNewTabItemContent('<p>Lorem Ipsum ...</p>', index+1));
	$ck('.editfocus .tabsck').tabsck( "refresh" );
	// add the element for edition
	var itemedition = ckCreateEditItem(index, $ck('#items_edition_list'), 'Lorem Ipsum ...', '<p>Lorem Ipsum ...</p>');
	ckMakeEditItemAccordion(itemedition);
//	tinymce.execCommand('mceAddEditor', false, 'item_content_' + index);
	ckLoadEditorOnTheFly('item_content_' + index);
}

function ckGetNewTabItemTitle(title, index) {
	var html = '<li><a href="#<?php echo $id; ?>_tabs-'+index+'" class="itemtitleck">'+title+'</a></li>'

	return html;
}

function ckGetNewTabItemContent(content, index) {
	var html = '<div id="<?php echo $id; ?>_tabs-'+index+'" class="tabck itemcontentck">'
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
	ckUpdatePreviewArea();

	var activetab = 0;
	$ck('#popup_editionck .item_edition').each(function(i, el) {
		if ($ck(el).find('.item_setdefault.active').length) {
			activetab = i;
		}
	});

	focus.find('.tabsck').attr('activetab', activetab);
	$ck('.tabsck', focus).tabsck("destroy");
	$ck('.tabsck', focus).tabsck({active: activetab});
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
	var index_item = item.index('.item_edition');
	ckRemoveEditorOnTheFly(item.find('.item_content_edition').attr('id'));

	$ck('.editfocus .tabsck > ol > li').eq(index_item).remove();
	$ck('.editfocus .tabsck .tabck').eq(index_item).remove();
	$ck( "#<?php echo $id; ?>_preview_tabs" ).tabsck("refresh");
}

function ckUpdatePreviewArea() {
	$ck('#items_edition_list .item_title_edition').each(function(i, el) {
		$ck('.editfocus .itemtitleck').eq(i).text($ck(el).val());
//		var content = $ck('#items_edition_list .item_content_edition').eq(i).val();
//		content = ckEditorToContent(content);
//		$ck('.editfocus .itemcontentck').eq(i).html(content);
		$ck('.editfocus .itemcontentck').eq(i).html(ckEditorToContent($ck('#items_edition_list .item_content_edition').eq(i).val()));
	});
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
		stop: function( event, ui ) {
			$ck(this).find('.item_content_edition:not(.ui-sortable-helper)').each(function(){
				ckLoadEditorOnTheFly($ck(this).attr('id'));
			});
			ckUpdatePreviewArea();
			$ck( "#<?php echo $id; ?>_preview_tabs" ).tabsck("refresh");
		},
	});
}

</script>