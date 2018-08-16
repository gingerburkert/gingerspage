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

class PagebuilderckViewElement extends JViewLegacy {

	function display($tpl = null) {
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

		// get instance of the editor to load the css / js in the page
		$this->ckeditor = PagebuilderckHelper::loadEditor();

		$input = JFactory::getApplication()->input;

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'), 'home_pagebuilderck');

		$this->item = $this->get('Data');
		$this->elements = $this->get('Elements');

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

		$this->addToolbar();
		parent::display($tpl);

		// exit to make a full edition standalone page
		if ($input->get('display') === 'raw') {
			exit();
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		require_once JPATH_COMPONENT . '/helpers/pagebuilderck.php';

		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$state = $this->get('State');
		$canDo = PagebuilderckHelper::getActions();

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		// For new records, check the create permission.
		if ($isNew && $user->authorise('core.create', 'com_pagebuilderck'))
		{
			JToolbarHelper::apply('element.apply');
			JToolbarHelper::save('element.save');
			// JToolbarHelper::save2new('element.save2new');
			JToolbarHelper::cancel('element.cancel');
		} else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('element.apply');
					JToolbarHelper::save('element.save');
//					JToolbarHelper::custom('element.restore', 'archive', 'archive', 'CK_RESTORE', false);
					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						// JToolbarHelper::save2new('element.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				// JToolbarHelper::save2copy('element.save2copy');
			}

			JToolbarHelper::cancel('element.cancel', 'JTOOLBAR_CLOSE');
		}

		// JToolbarHelper::divider();
		// JToolbarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER_EDIT');
	}
}
