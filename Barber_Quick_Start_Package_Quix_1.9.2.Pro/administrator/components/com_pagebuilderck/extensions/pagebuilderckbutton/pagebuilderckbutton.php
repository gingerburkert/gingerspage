<?php
/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

/**
 * Editor Pagebuilderckbutton buton
 *
 */
class PlgButtonPagebuilderckbutton extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Pagebuilderckbutton button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 */
	public function onDisplay($name)
	{
		// loads the language files from the component frontend
		$lang	= JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);

		// load CKBox from the component
		include_once(JPATH_SITE . '/administrator/components/com_pagebuilderck/helpers/pagebuilderck.php');
		PagebuilderckHelper::loadCkbox();

		// instantiate variables
		$doc = JFactory::getDocument();
		$getContent = $this->_subject->getContent($name);

		// get the list of pages
		$pages = json_encode($this->getPages());

		// construct the JS code to manage the operations
		$js = "
			/* Function called on button click */
			function insertPagebuilderckbutton(editor) {
				pages = " . $pages . ";

//				event.preventDefault();
				var editor = tinyMCE.activeEditor;
				var content = editor.getContent({format: 'text'});
				var re = /{pagebuilderck\s+(.*?)\s*}/ig;
				matches = content.match(re);
				createPagebuilderckPopup();

				var PBCKmodal = jQuery('#pagebuilderckButtonModal');
				PBCKmodal.empty().append('<div class=\"inner\" />');
				PBCKmodalBody = PBCKmodal.find('> .inner').css('padding', '10px');
				PBCKmodalBody.append('<h4 class=pagebuilderckButtonModalTitle >" . JText::_('CK_NEW_TAG', true) . "</h4>');
				PBCKmodalBody.append('<div class=pagebuilderckButtonModalDesc >" . JText::_('CK_INSERT_NEW_DESC', true) . "</div>');
				PBCKmodalBody.append('<div class=\"clearfix\">'
					+'<a class=\"btn\" onclick=\"loadPagebuilderckModalEdition(0)\" title=\"" . JText::_('CK_CREATE_NEW_PAGE_DESC', true) . "\"><i class=\"icon icon-new\"></i>" . JText::_('CK_CREATE_NEW_PAGE', true) . "</a>'
					+'&nbsp;'
					+'<a class=\"btn\" onclick=\"loadPagebuilderckModalInsertChoice()\" title=\"" . JText::_('CK_INSERT_EXISTING_PAGE_DESC', true) . "\"><i class=\"icon icon-plus-2\"></i>" . JText::_('CK_INSERT_EXISTING_PAGE', true) . "</a>'
					+'</div>');
				if (matches && matches.length) {
					PBCKmodalBody.append('<hr style=\"margin:10px 0;\"/>');
					PBCKmodalBody.append('<h4 class=pagebuilderckButtonModalTitle >" . JText::_('CK_EXISTING_TAGS_FOUND', true) . "</h4>');
					PBCKmodalBody.append('<div class=pagebuilderckButtonModalDesc >" . JText::_('CK_EXISTING_TAGS_FOUND_DESC', true) . "</div>');
					for (var i = 0; i < matches.length; i++) {
						match = matches[i];
						var id = match.replace('pagebuilderck', '')
										.replace('{', '')
										.replace('}', '')
										.trim();
						addPagebuilderckEditionChoice(id);
					}
					return false;
				} else {
					// jInsertEditorText('<hr id=\"system-readmore\" />', editor);
				}
			}

			/* Called from the modal pages list, to insert the tag from the ID */
			function insertPagebuilderckTag(id) {
//				jInsertEditorText('{pagebuilderck '+id+'}', '" . $name . "');
				tinyMCE.activeEditor.execCommand('mceInsertContent', false, '{pagebuilderck '+id+'}');
				CKBox.close('#CKBoxPagebuilderckButtonModal .ckboxmodal-footer');
			}

			/* Create the popup */
			function createPagebuilderckPopup() {
				removeFooterSaveButton();
				if (! document.getElementById('pagebuilderckButtonModal')) {
					var popup = document.createElement('div');
					popup.id = 'pagebuilderckButtonModal';
					popup.className = 'pagebuilderckButtonModal';
					popup.style.height = '100%';
					document.body.appendChild(popup);
					popup.innerHTML = ''
							+'<h3>" . JText::_('CK_EDIT', true) . "</h3>'
						+'';
				}
				var PBCKmodal = jQuery('#pagebuilderckButtonModal');

				CKBox.open({handler: 'inline', content: 'pagebuilderckButtonModal', style: {padding: '10px'}, id: 'CKBoxPagebuilderckButtonModal' });
			}

			/* Add a line for each existing page to edit */
			function addPagebuilderckEditionChoice(id) {
				pages = " . $pages . ";
				var PBCKmodal = jQuery('#pagebuilderckButtonModal');
				var pagetitle = (typeof(pages[id]) != 'undefined') ? '" . JText::_('CK_TITLE', true) . ": <span class=\"label cktitle\">' + pages[id]['title'] + '</span>' : '';
				PBCKmodal.append('<div class=pagebuilderckButtonModalChoice><span class=\"\">" . JText::_('CK_ID', true) . "</span> : <span class=\"label label-info ckid\">'+id+'</span>' + pagetitle + '<a class=\"btn ckedit\" data-id='+id+' onclick=\"loadPagebuilderckModalEdition('+id+')\" ><i class=\"icon icon-edit\"></i>" . JText::_('CK_EDIT', true) . "</a></div>');
			}

			/* Load the list of pages to select the one to insert */
			function loadPagebuilderckModalInsertChoice() {
				var PBCKmodal = jQuery('#pagebuilderckButtonModal');
				PBCKmodal.empty();
				PBCKmodal.prepend('<iframe class=\"iframe\" src=\"".JUri::base(true)."/index.php?option=com_pagebuilderck&view=pages&amp;layout=modal&amp;function=insertPagebuilderckTag&amp;tmpl=component\"></iframe>');
			}

			/* Load the edition area for the selected page */
			function loadPagebuilderckModalEdition(id) {
				var PBCKmodal = jQuery('#pagebuilderckButtonModal');
				removeFooterSaveButton();
				addFooterSaveButton();
				
				PBCKmodal.empty();
				PBCKmodal.prepend('<iframe class=\"iframe\" src=\"".JUri::base(true)."/index.php?option=com_pagebuilderck&view=page&amp;layout=modal&amp;id='+id+'&amp;tmpl=component\"></iframe>');
				if (id == 0) {
					addInsertNewPageButton();
				}
			}

			/* Add the insert new page button */
			function addInsertNewPageButton() {
				jQuery('#pagebuilderckButtonModal').find('iframe').load(function() {
					var idval = jQuery('#pagebuilderckButtonModal').find('iframe').contents().find('input[name=\"id\"]').val();
					if (idval != '') {
						jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-footer').append('<button class=\"ckboxmodal-button btn btn-success saveck\" data-dismiss=\"modal\" aria-hidden=\"true\" onclick=\"jQuery(\'iframe\', jQuery(jQuery(this).parents(\'.ckboxmodal\')[0])).contents().find(\'#applyBtn\').click();insertPagebuilderckTag(' + idval + ')\"><i class=\"icon icon-checkmark\"></i>" . JText::_('CK_INSERT_PAGE_AND_CLOSE', true) . "</button>');
					}
					// add automatically the title for the page
					if (jQuery('#jform_title').length && jQuery('#jform_title').val()) {
						jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-body iframe').contents().find('input[name=\"title\"]').val(jQuery('#jform_title').val());
					}
				});
				jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-footer .savecloseck').hide();
			}

			/* Add the save button */
			function addFooterSaveButton() {
				jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-footer').append('<button class=\"ckboxmodal-button btn btn-success saveck savecloseck\" data-dismiss=\"modal\" aria-hidden=\"true\" onclick=\"jQuery(\'iframe\', jQuery(jQuery(this).parents(\'.ckboxmodal\')[0])).contents().find(\'#saveBtn\').click();CKBox.close(\'#CKBoxPagebuilderckButtonModal .ckboxmodal-footer\', \'1\');\"><i class=\"icon icon-checkmark\"></i>" . JText::_('CK_SAVE_CLOSE', true) . "</button>');
				jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-footer').append('<button class=\"ckboxmodal-button btn btn-success saveck\" aria-hidden=\"true\" onclick=\"jQuery(\'iframe\', jQuery(jQuery(this).parents(\'.ckboxmodal\')[0])).contents().find(\'#applyBtn\').click();\"><i class=\"icon icon-checkmark\"></i>" . JText::_('CK_APPLY', true) . "</button>');
			}

			/* Remove the save button (only needed on edition mode) */
			function removeFooterSaveButton() {
				jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-footer .saveck').remove();
				jQuery('#CKBoxPagebuilderckButtonModal').find('.ckboxmodal-footer .applyck').remove();
			}
			";

		$css = '/** fullscreen mode for the Page Builder CK modal **/
.pagebuilderckButtonModalFullscreen {
	top: 0 !important;
	left: 0 !important;
	right: 0 !important;
	margin: 0 !important;
	width: 100% !important;
	box-sizing: border-box;
	height: 100% !important;
}

.pagebuilderckButtonModalFullscreen .ckboxmodal-body {
	max-height: 100% !important;
	box-sizing: border-box;
}

.pagebuilderckButtonModalFullscreen iframe {
	height: 100% !important;
}

.pagebuilderckButtonModalDesc {
	padding: 0 0 10px 0;
}

.pagebuilderckButtonModalChoice {
    border-bottom: 1px solid #ddd;
    padding: 5px;
}

.pagebuilderckButtonModalChoice .label {
	margin: 0 7px;
}

.pagebuilderckButtonModalChoice > span {
	display: inline-block;
}

.pagebuilderckButtonModalChoice > span.ckid {
	min-width: 15px;
	text-align: center;
}

.pagebuilderckButtonModalChoice > span.cktitle {
	min-width: 100px;
}

.pagebuilderckButtonModalChoice > span.ckedit {

}

.pagebuilderckButtonModal .ckboxmodal-body iframe {
	border: 0 none !important;
	max-height: none;
	width: 100%;
}

#CKBoxPagebuilderckButtonModal .icon {
	margin-right: 3px;
}

';

		$doc->addScriptDeclaration($js);
		$doc->addStyleDeclaration($css);
		$button = new JObject;
		$button->modal = false;
		$button->class = 'btn hasTooltip';
		$button->onclick = 'insertPagebuilderckbutton(\'' . $name . '\');return false;';
		$button->text = JText::_('PLG_PAGEBUILDERCKBUTTON');
		$button->name = 'grid';
		$button->title = JText::_('PLG_PAGEBUILDERCKBUTTON_DESC');

		$button->link = '#';

		return $button;
	}

	/*
	 * Get the list of published pages with names
	 *
	 * Return Array - The list of pages
	 */
	public function getPages() {
		$db = JFactory::getDbo();
		$q = "SELECT title, id FROM #__pagebuilderck_pages WHERE state=1";
		$db->setQuery($q);
		$db->query();
		$pages = $db->loadObjectlist('id');

		return $pages;
	}
}
