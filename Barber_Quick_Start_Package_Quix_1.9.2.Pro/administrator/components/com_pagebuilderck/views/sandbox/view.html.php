<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class PagebuilderckViewSandbox extends JViewLegacy {

	function display($tpl = null) {
		$app = JFactory::getApplication();
		$input = $app->input;
		$input->set('tmpl', 'component');
		
		$editor = $input->get('editor', 'pbckhidden', 'string');
		$doc = JFactory::getDocument();
		// var_dump ($doc);
		// echo ($doc->_script['text/javascript']);
		
		// include_once JPATH_SITE . '/libraries/joomla/document/html/renderer/head.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
		
		
		$this->ckeditor = PagebuilderckHelper::loadEditor();
		$this->ckeditor->display($editor, '', '', '', '','', $buttons = true);
		
		
		// var_dump ($doc);
		
		
		// echo '<pre>' . $doc->_script['text/javascript'] . '</pre>';
// _scripts (array)
// _styleSheets (array)
// _style (array)
// _custom (array)


  // public '_custom' => 
    // array (size=1)
      // 0 => string '<link rel="stylesheet" href="/joomla31fr/administrator/index.php?option=com_jce&view=editor&layout=editor&task=pack&type=css&component_id=10751&wf192d8a523d1ad6a8ad4754de4c01ef12=1" type="text/css" />
  // <script data-cfasync="false" type="text/javascript" src="/joomla31fr/administrator/index.php?option=com_jce&view=editor&layout=editor&task=pack&component_id=10751&wf192d8a523d1ad6a8ad4754de4c01ef12=1"></script>
  // <script data-cfasync=
  // =========================================
// public '_scripts' => 
    // array (size=13)
      // '/joomla31fr/media/system/js/mootools-core.js' => 
        // array (size=3)
          // 'mime' => string 'text/javascript' (length=15)
          // 'defer' => boolean false
          // 'async' => boolean false
  // =========================================
// public '_script' => 
    // array (size=1)
      // 'text/javascript' => string 'jQuery(document).ready(function (){
				// jQuery('.pagebuilderckcheck
  // =========================================
				// public '_styleSheets' => 
    // array (size=6)
      // '/joomla31fr/media/system/css/modal.css' => 
        // array (size=3)
          // 'mime' => string 'text/css' (length=8)
          // 'media' => null
          // 'attribs' => 
            // array (size=0)
  // =========================================
			// public '_style' => 
    // array (size=1)
      // 'text/css' => string '/** fullscreen mode for the Page Builder CK modal **/
// .pagebuilderckButtonModalFullscreen {
	// top: 0 !important;
	// left: 0 !important;

	
		// 1/ css files
		// 2/ css inline
		// 3/ js files
		// 4/ js inline
		// 5/ custom
		// write the inline scripts 
		// foreach ($doc->_styleSheets as $sheet => $array) {
		// <link rel="stylesheet" href="/joomla31fr/media/jui/css/chosen.css" type="text/css" />
			// echo '<link rel="stylesheet" ';
		// }
		
		// write scripts files
		// foreach ($doc->_scripts as $script => $array) {
			// echo  $script . '</pre>';
		// }
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('head');
		// var_dump(JDocumentRendererHead::render());
		echo $renderer->render(null);
		exit();
		// echo ($doc->_script['text/javascript']);
		// parent::display($tpl);
		
		
		

	}
}
