<?php
/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

if (! file_exists(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderckfront.php')) return;

// get the html code of the page
$modulecontent = $params->get('pagedition');

// pass through the content plugins 
if ($params->get('preparecontent', '0')) {
	JPluginHelper::importPlugin('content');
	$modulecontent = JHtml::_('content.prepare', $modulecontent, '', 'mod_pagebuilderck.content');
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// frontend medias
PagebuilderckFrontHelper::loadFrontendAssets();

// get the page model
include_once JPATH_ROOT . '/components/com_pagebuilderck/models/page.php';
$model	= JModelLegacy::getInstance('Page', 'PagebuilderckModel');

// parse the html code through the model page
$model->parseHtml($modulecontent);

require JModuleHelper::getLayoutPath('mod_pagebuilderck', $params->get('layout', 'default'));
