<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */


// no direct access
defined('_JEXEC') or die;

include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/defines.php';

// Include dependancies
jimport('joomla.application.component.controller');
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

// load admin language file for editing mode
if (JFactory::getApplication()->input->get('view') === 'edit') {
	$lang	= JFactory::getLanguage();
	$lang->load('com_pagebuilderck', JPATH_ADMINISTRATOR . '/components/com_pagebuilderck', $lang->getTag(), false);
}

$controller	= JControllerLegacy::getInstance('Pagebuilderck');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
