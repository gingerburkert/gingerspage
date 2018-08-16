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
jimport('joomla.filesystem.folder');

class PagebuilderckViewModules2 extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/pagebuilderck.php';
		
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if (JFactory::getApplication()->isAdmin()) $this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		PagebuilderckHelper::loadCkbox();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		$state = $this->get('State');
		$canDo = PagebuilderckHelper::getActions($state->get('filter.category_id'));

		// Load the left sidebar.
		PagebuilderckHelper::addSubmenu('modules2');

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));
	}
}
