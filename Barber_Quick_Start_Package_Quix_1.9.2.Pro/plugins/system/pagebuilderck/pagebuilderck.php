<?php

/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

//include_once JPATH_ROOT . '/components/com_pagebuilderck/models/page.php';

class plgSystemPagebuilderck extends JPlugin {

	public $pluginPath;

	private $styledeclarationcalled = false;

	/*
	 * Constructor
	 */
	function __construct(&$subject, $config) {
		$this->pluginPath = '/plugins/system/pagebuilderck';

		if (! file_exists(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderckfront.php')) return;

		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/defines.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderckfront.php';

		parent :: __construct($subject, $config);
	}

	function onBeforeRender() {
		PagebuilderckFrontHelper::addStyleDeclaration($this->getResponsiveCss());
		// loads all the css in the final stage
		PagebuilderckFrontHelper::loadAllCss();
	}
	/**
	 * @param       JForm   The form to be altered.
	 * @param       array   The associated data for the form.
	 * @return      boolean
	 */
	public function onContentPrepareForm($form, $data) {

		if ($form->getName() != 'com_content.article')
			return;

		$conf = JFactory::getConfig();
		$app = JFactory::getApplication();
		$input = $app->input;

		// save the default editor value
		$conf->set('pagebuilderck_replaced_editor', $conf->get('editor'));
		// tells the system that page builder ck editor is allowed here for the editor button
		$conf->set('pagebuilderck_allowed', '1');

		// get the language
		$this->loadLanguage();

		// load the additional options in the module
		JForm::addFormPath(JPATH_SITE . $this->pluginPath . '/params');
		$form->loadFile('pagebuilderck_article_params', false);

		// on page load, force the editor to switch on page builder if needed
		if (! empty($data)) {
			// get article params // make JObject-Array check because of issue in J!3.7
			if (method_exists($data,'get')) {
				$attribs = $data->get('attribs');
			} else if (is_object($data) && isset($data->attribs)) {
				$attribs = $data->attribs;
			} else if (isset($data['attribs'])) {
				$attribs = $data['attribs'];
			}
			// on front end the params are not array, so we must do it yourself
			if (! is_array($attribs)) {
				$attribs = json_decode($attribs, true);
			}

			// if the user has switched to the pbck editor
			if ($input->get('pbck') == '1') {
				$attribs['pagebuilderck_editor'] = '1';
				// set article params // make JObject-Array check because of issue in J!3.7
				if (method_exists($data,'get')) {
					$data->set('attribs', $attribs);
				} else if (is_object($data) && isset($data->attribs)) {
					$data->attribs = $attribs;
				} else if (isset($data['attribs'])) {
					$data['attribs'] = $attribs;
				}
				$conf->set('editor', 'none');
			}
			// if the article has already been saved with pbck
			if (isset($attribs['pagebuilderck_editor']) && $attribs['pagebuilderck_editor'] == '1') {
				$conf->set('editor', 'none');
				$input->set('pbck', '1');
			}
			
//			if (isset($data->introtext)) {
//				$data->introtext = str_replace("|URIROOT|", JUri::root(true), $data->introtext);
//			}
		}
	}

	/*
	 * Look for the tag for replacement
	 *
	 */
	// public function onAfterRender() {
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		// if ($context == 'mod_pagebuilderck.content'
				// || $context == 'com_pagebuilderck.page') return;

		$app = JFactory::getApplication();
		// get the page code
		$body = $article->text;

		// test if the page is integrated into the article 
		if (stristr($article->text, "class=\"rowck") && $app->isSite()
				&& $context != 'mod_pagebuilderck.content'
				&& $context != 'com_pagebuilderck.page'
			) {
			// if we call a page inside the interface using tag
			if (stristr($article->text, "{pagebuilderck")) {
				$this->callAssets();

				// look for the tags and replace
				$regex = "#{pagebuilderck(.*?)}#s"; // masque de recherche pour le tag
				$body = preg_replace_callback($regex, array($this, 'callPageFromTag'), $body);

				$article->text = $body;
			}

			include_once JPATH_ROOT . '/components/com_pagebuilderck/models/page.php';
			$this->callAssets();

			$model = JModelLegacy::getInstance('Page', 'PagebuilderckModel');
			$model->parseHtml($article->text);
			$article->text = '<div class="pagebuilderck">' . $article->text . '</div>';
		}
		// test if the tag if called from a normal article, if not then return directly
		else if (stristr($article->text, "{pagebuilderck")) {
			$this->callAssets();

			// look for the tags and replace
			$regex = "#{pagebuilderck(.*?)}#s"; // masque de recherche pour le tag
			$body = preg_replace_callback($regex, array($this, 'callPageFromTag'), $body);

			$article->text = $body;
		}

		return;
	}

	/*
	 * Replace the tag by the page html
	 *
	 * @matches Array of found tags
	 *
	 * return string the html code of the page to load in the final body
	 */
	private function callPageFromTag($matches) {
		if (isset($matches[1])) {
			$id = (int)trim($matches[1]);
		} else {
			return false;
		}
		$html = $this->getPageHtml($id);
		/*if ($html) {
			$this->callAssets();
		}*/
		return '<div class="pagebuilderck pagebuilderck' . $id . '">' . $html . '</div>';
	}

	/*
	 * Get the page html from the component
	 *
	 * @id int the page ID
	 *
	 * return string the html code of the page
	 */
	private function getPageHtml($id) {
		include_once JPATH_ROOT . '/components/com_pagebuilderck/models/page.php';
		$model = JModelLegacy::getInstance('Page', 'PagebuilderckModel');
		$page = $model->getItem($id);
		if (isset($page->htmlcode) && $page->htmlcode) {
			return $page->htmlcode;
		}
		return '';
	}

	private function callAssets() {
		PagebuilderckFrontHelper::loadFrontendAssets();
		$doc = JFactory::getDocument();
//		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
//		PagebuilderckFrontHelper::addStyleSheet(JUri::root(true) . '/components/com_pagebuilderck/assets/pagebuilderck.css');
//		$doc->addStyleSheet(JUri::root(true) . '/components/com_pagebuilderck/assets/font-awesome.min.css');
//		$doc->addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/jquery-uick.js');
//		$doc->addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/pagebuilderck.js');
		if ($this->styledeclarationcalled == false) {
			PagebuilderckFrontHelper::addStyleDeclaration($this->getResponsiveCss());
			$this->styledeclarationcalled = true;
		}
	}

	public function getResponsiveCss() {
		$componentParams = JComponentHelper::getParams('com_pagebuilderck');
		$css = '';
		$css .= '@media only screen and (min-width:' . ((int)$componentParams->get('responsive4value', '800')+1) . 'px){
.ckhide5 { display: none !important; } 
.ckstack5 > .inner { display: block; }
.ckstack5 .blockck { float: none !important; width: auto !important; display: block; margin-left: 0 !important;}
}';
		$css .= '@media only screen and (max-width:' . (int)$componentParams->get('responsive4value', '800') . 'px) and (min-width:' . ((int)$componentParams->get('responsive3value', '640')+1) . 'px){
.ckhide4 { display: none !important; } 
.ckstack4 > .inner { display: block; }
.ckstack4 .blockck { float: none !important; width: auto !important; display: block; margin-left: 0 !important;}
}';
		$css .= '@media only screen and (max-width:' . (int)$componentParams->get('responsive3value', '640') . 'px) and (min-width:' . ((int)$componentParams->get('responsive2value', '480')+1) . 'px){
.ckhide3 { display: none !important; } 
.ckstack3 > .inner { display: block; }
.ckstack3 .blockck { float: none !important; width: auto !important; display: block; margin-left: 0 !important;}
}';
		$css .= '@media only screen and (max-width:' . (int)$componentParams->get('responsive2value', '480') . 'px) and (min-width:' . ((int)$componentParams->get('responsive1value', '320')+1) . 'px){
.ckhide2 { display: none !important; } 
.ckstack2 > .inner { display: block; }
.ckstack2 .blockck { float: none !important; width: auto !important; display: block; margin-left: 0 !important;}
}';
		$css .= '@media only screen and (max-width:' . (int)$componentParams->get('responsive1value', '320') . 'px){
.ckhide1 { display: none !important; } 
.ckstack1 > .inner { display: block; }
.ckstack1 .blockck { float: none !important; width: auto !important; display: block; margin-left: 0 !important;}
}';
		return $css;
//		JFactory::getDocument()->addStyleDeclaration($css);
	}
}