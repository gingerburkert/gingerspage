<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined( '_JEXEC' ) or die;

// Access check.
if ( !JFactory::getUser()->authorise( 'core.manage', 'com_quix' ) ) {
  return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

// Include dependancies
jimport( 'quix.app.bootstrap' );
jimport( 'quix.app.init' );

$controller = JControllerLegacy::getInstance( 'Quix' );
$controller->execute( JFactory::getApplication()->input->get( 'task' ) );
$controller->redirect();
