<?php
/**
 * @copyright	Copyright (C) 2016 Cédric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

/**
 * Editor Pagebuilderckeditor button
 *
 */
class PlgButtonPagebuilderckeditor extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Pagebuilderckeditor button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 */
	public function onDisplay($name)
	{

		// check the name of the editor, if ckeditor then we must not load this button because we are already in the pagebuilder
		if ($name == 'ckeditor') return;

		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		$input = $app->input;
		$conf = JFactory::getConfig();
		$css = "";

		// authorize only in article edition admin and front, if the page builder ck editor has been allowed, comes from the system plugin
		if ($conf->get('pagebuilderck_allowed') != '1')
			return;

		// loads the language files from the component frontend
		$lang = JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);

		// if the page builder ck editor must be used
		if ($input->get('pbck', '0') == '1') {
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

			// get instance of the editor to load the css / js in the page
			// $this->ckeditor = PagebuilderckHelper::loadEditor();
			// need the tinymce instance for the items edition
			// Load the editor Tinymce or JCE
			$editor = $conf->get('pagebuilderck_replaced_editor') == 'jce' ? 'jce' : 'tinymce';
			$editor = JEditor::getInstance($editor);
			$editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
			// Get an instance of the model
			JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_pagebuilderck/models', 'PagebuilderckModel');
			$model = JModelLegacy::getInstance('Elements', 'PagebuilderckModel', array('ignore_request' => true));
			// $model = $this->getModel('Elements', '', array());
			$this->elements = $model->getItems();
// var_dump($this->elements);
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/default_include.php'));
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/default_menu.php'));
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/default_toolbar.php'));

			// force the inclusion of the field with the value 1
			echo '<input id="jform_attribs_pagebuilderck_editor" type="hidden" value="1" name="jform[attribs][pagebuilderck_editor]">';

			$js1 = "
			function pbckeditorLoadEditor(name) {
				var cont = jQuery(name).parent();
				// cont.css('display', 'none');
				var html = '<div id=\"workspaceparentck\">'
								+'<div id=\"workspaceck\" class=\"workspaceck\">'
								+'</div>'
							+'</div>';
				// load the page builder workspace and hide the textarea
				//cont.children().hide();
				cont.append(html);

				var injectContentToPbck = false;
				// test if this is a new content or already created with page builder
				if (jQuery(name).length && ! jQuery(name).val().test(/rowck/)) {
					injectContentToPbck = true;
				}

				jQuery('#workspaceparentck').prepend(jQuery('#menuck').show());
				jQuery('#workspaceparentck').prepend(jQuery('#pagetoolbarck').show());
				if (jQuery(name).length && ! injectContentToPbck) {
					jQuery('#workspaceck').html(jQuery(name).val().replace(/\|URIROOT\|/g, '" . JUri::root(true) . "'));
				}

				initWorkspace();
				if (injectContentToPbck) {
					jQuery('#workspaceck .blockck > .inner > .innercontent').addClass('ckfocus');
					ckAddItem('text');
				}
				cont.css('display', '');
				
				// adds the settings in JS to be sure that it is at the end of the front end form
				jQuery('#adminForm').append('<input id=\"jform_attribs_pagebuilderck_editor\" type=\"hidden\" value=\"1\" name=\"jform[attribs][pagebuilderck_editor]\">');
			}
			
			// Override to get the appended text ID and update the data
			function ckTriggerAfterAdditem(id) {
				var content = jQuery('#" . $name . "').val();
				content = ckEditorToContent(content);
				jQuery('#'+id+' > .inner').html(content);
			}

			JoomlaCK.beforesubmitbutton = function(task) {
				var workspace = jQuery('#workspaceck');
				jQuery('#" . $name . "').attr('value', workspace.html());

				// JoomlaCK.submitbutton(task);
			}
			";

			echo "<script>" . $js1 . "</script>";
			
			$css .= "#jform_articletext,
#jform_articletext + #editor-xtd-buttons,
#jform_text,
#jform_text + #editor-xtd-buttons
 {
	display: none;
}";
		}

		// construct the JS code to manage the operations
		$js2 = "
//			jQuery(window).load(function (){
			jQuery(document).ready(function (){
				if (" . $input->get('pbck', '0') . " != '1') pbckeditorLoadEditorButton('#" . $name . "');
				if (" . $input->get('pbck', '0') . " == '1') pbckeditorLoadEditor('#" . $name . "');
			});

			function pbckeditorLoadEditorButton(name) {
				var cont = jQuery(name).parent();
				cont.before('<a class=\"btn pbckswitch\" onclick=\"pbckLoadPagebuilderckEditor()\"><i class=\"icon icon-loop\"></i>&nbsp;" . JText::_('CK_LOAD_PAGEBUILDERCK_EDITOR', true) . "</a>');
			}

			function pbckLoadPagebuilderckEditor() {				
				var beSure = confirm('" . JText::_('CK_CONFIRM_PAGEBUILDERCK_EDITOR', true) . "');
				if (!beSure) return;

				window.location.search += '&pbck=1';
			}
			";
		$doc->addScriptDeclaration($js2);

		$css .= ".pbckswitch {
	margin: 5px 0;
}";
		$doc->addStyleDeclaration($css);

		return;
	}
}
