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

class PagebuilderckViewOptions extends JViewLegacy {

	function display($tpl = null) {

		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';

		// load the helper class to construct the styles areas
		$this->menustyles = new MenuStyles();
		$this->imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/menustyles/';
		$input = new JInput();
		$this->cktype = $input->get('cktype', null);

		if (! $this->cktype && $input->get('layout', null, 'cmd') !== 'editor') {
			echo JText::_('COM_PAGEBUILDERCK_ERROR_LAYOUT');
			exit();
		}
		$user		= JFactory::getUser();
		$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
		if (! $canEdit) exit();

		parent::display($tpl);
		exit();
	}
}
