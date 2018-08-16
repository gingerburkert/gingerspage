<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die;

// Version warning
if (version_compare(phpversion(), '5.5', '<')) 
{
	$lang = JFactory::getLanguage();
	$extension = 'com_quix';
	$base_dir = JPATH_ADMINISTRATOR;
	$language_tag = 'en-GB';
	$reload = true;
	$lang->load($extension, $base_dir, $language_tag, $reload);

	$layout = new JLayoutFile('toolbar.phpwarning', JPATH_COMPONENT_ADMINISTRATOR . '/layouts');
	echo $layout->render(array());
	return true;
}

// Include dependencies
jimport( 'quix.app.bootstrap' );
jimport( 'quix.app.init' );
JLoader::register( 'QuixFrontendHelper', JPATH_COMPONENT . '/helpers/quix.php' );


// Execute the task.
$controller = JControllerLegacy::getInstance( 'Quix' );
$controller->execute( JFactory::getApplication()->input->get( 'task' ) );
$controller->redirect();
