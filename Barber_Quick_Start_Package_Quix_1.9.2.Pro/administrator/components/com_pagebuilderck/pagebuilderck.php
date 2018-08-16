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

define('PAGEBUILDERCK_PATH', dirname(__FILE__));

include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/defines.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_pagebuilderck')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// loads the language files from the frontend
$lang	= JFactory::getLanguage();
$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);
$lang->load('com_pagebuilderck', JPATH_SITE, $lang->getTag(), false);

// loads the helper in any case
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Pagebuilderck');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
