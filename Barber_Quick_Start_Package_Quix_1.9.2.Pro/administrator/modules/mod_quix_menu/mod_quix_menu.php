<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Initialise variables.
$lang		= JFactory::getLanguage();
$user		= JFactory::getUser();
$app		= JFactory::getApplication();
$hideMainmenu	= $app->input->get('hidemainmenu')  ;

$show_quix_menu 	= $params->get('show_quix_menu', 1);
if ($show_quix_menu) {
	$hideMainmenu=false;
}

// Render the module layout
require JModuleHelper::getLayoutPath('mod_quix_menu', $params->get('layout', 'default'));
