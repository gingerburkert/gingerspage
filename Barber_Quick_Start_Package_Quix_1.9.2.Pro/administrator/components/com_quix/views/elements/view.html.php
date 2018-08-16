<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * View class for a list of Quix.
 *
 * @since  1.6
 */
class QuixViewElements extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		QuixHelper::addSubmenu('elements');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/quix.php';

		$state = $this->get('State');
		$canDo = QuixHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_QUIX_TITLE_ELEMENTS_MANAGER'), 'generic');
		
		$bar = JToolBar::getInstance('toolbar');
		$layout = new JLayoutFile('toolbar.collapse');
		$bar->appendButton('Custom', $layout->render(array()), 'collapse');
		
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_quix');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::divider();
			$bar = JToolBar::getInstance('toolbar');

			// Instantiate a new JLayoutFile instance and render the layout
			JHtml::_('behavior.modal', 'a.quixSettings');
			$layout = new JLayoutFile('toolbar.mysettings');

			$bar->appendButton('Custom', $layout->render(array()), 'mysettings');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_quix&view=elements');
		
	}
}
