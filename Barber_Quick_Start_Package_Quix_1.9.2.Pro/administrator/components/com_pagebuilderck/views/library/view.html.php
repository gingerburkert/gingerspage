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

class PagebuilderckViewLibrary extends JViewLegacy {

	function display($tpl = null) {
		$input = JFactory::getApplication()->input;

		$user = JFactory::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		$url = 'https://media.joomlack.fr/api/pagebuilderck/pages';
		// for local test purpose only
//		if ($input->getInt('sandbox' == '1')) {
//			$url = 'http://localhost/media.joomlack.fr';
//		}
		// store the library in the session to avoid to much request to the external server
//		$session = JFactory::getSession();
		// if (! $this->itemsCats = $session->get('pagebuilderck_library_categories')) {
		// try {
				// $itemsCats = file_get_contents($url);
		// } catch (Exception $e) {
				// echo 'ERROR : Unable To connect to the library server. Check your internet connection and retry later. ',  $e->getMessage(), "\n";
			// exit();
		// }
			// $this->itemsCats = json_decode($itemsCats);
			// $session->set('pagebuilderck_library_categories', $this->itemsCats);
		// }

		parent::display($tpl);

		exit();
	}
}
