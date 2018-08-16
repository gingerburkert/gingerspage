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
	<div class="menulink" tab="tab_edition"><?php echo JText::_('CK_TEXT_EDITION'); ?></div>
	<div class="tab menustyles ckproperty tab_fullscreen" id="tab_edition">
		<?php // echo PagebuilderckHelper::renderEditionButtons(); ?>
		<textarea id="<?php echo $id; ?>_text" class="joomla-editor-tinymce"></textarea>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc') ?>
	</div>
</div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	var textID = '<?php echo $id; ?>_text';

	content =  focus.find('.cktext').html();
	// var search = /<img(.*?)src="<?php echo str_replace('/', '\/', JUri::root(true)); ?>\/(.*?)"/g;
	// content = content.replace(search, '<img src="$2"');
	content = ckContentToEditor(content);
	$ck('#' + textID).val(content);
	
//	$ck('#previewarea .ckstyle').html(focus.find('.ckstyle').html());
//	$ck('#previewarea .cktype').html(focus.find('.cktext').html());

	ckLoadEditorOnTheFly(textID);

	ckFillEditionPopup(focus.attr('id'));
}

function ckBeforeSaveEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckSaveEditorOnTheFly(textID);
	var content = $ck('#' + textID).val();
	content = ckEditorToContent(content);

	var focus = $ck('.editfocus');
	focus.find('.cktext').html(content);
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

/*
 * Method automatically called in ckCloseEditionPopup() if exists
 */
function ckBeforeCloseEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckRemoveEditorOnTheFly(textID);
}

function ckSaveInlineEditionPopup() {
	ckBeforeSaveEditionPopup();
}

function ckUpdatePreviewArea() {

}

</script>