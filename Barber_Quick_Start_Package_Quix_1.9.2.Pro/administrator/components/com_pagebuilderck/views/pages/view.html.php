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

/**
 * View class for a list of Templateck.
 */
class PagebuilderckViewPages extends JViewLegacy {

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
		PagebuilderckHelper::addSubmenu('pages');

		JToolBarHelper::title(JText::_('COM_PAGEBUILDERCK'));

		//Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/page';
		if (file_exists($formPath)) {

			if ($canDo->get('core.create')) {
				JToolBarHelper::addNew('page.add', 'JTOOLBAR_NEW');
			}

			if ($canDo->get('core.edit')) {
				JToolBarHelper::editList('page.edit', 'JTOOLBAR_EDIT');
				JToolBarHelper::custom('page.copy', 'copy', 'copy', 'CK_COPY');
				// if Params is installed
				if (PagebuilderckHelper::getParams()) {
					$importButton = '<button class="btn btn-small" onclick="CKBox.open({handler: \'inline\', content: \'ckImportModal\', fullscreen: false, size: {x: \'600px\', y: \'200px\'}});">
										<span class="icon-forward-2"></span>
										' . JText::_('CK_IMPORT') . '
									</button>';
					$bar->appendButton('Custom', $importButton, 'import');
					
					$exportButton = '<button class="btn btn-small" onclick="ckExportPage(document.adminForm);">
										<span class="icon-share"></span>
										' . JText::_('CK_EXPORT') . '
									</button>';
					$bar->appendButton('Custom', $exportButton, 'export');

					// if (document.adminForm.boxchecked.value==0){alert('Veuillez d\'abord effectuer une s�lection dans la liste.');}else{ Joomla.submitbutton('pages.export')}
					// JToolBarHelper::custom('pages.export', 'share', 'share', 'CK_EXPORT', true);
					if ($importClass = PagebuilderckHelper::getParams('import')) {
						$importClass->loadImportForm();
					}
					if ($exportClass = PagebuilderckHelper::getParams('export')) {
						$exportClass->loadExportForm();
					}
				} else {
					$importButton = '<button class="btn btn-small" onclick="CKBox.open({handler:\'inline\',content: \'pagebuilderckparamsmessage\', fullscreen: false, size: {x: \'600px\', y: \'150px\'}});">
										<span class="icon-forward-2"></span>
										' . JText::_('CK_IMPORT') . '
									</button>';
					$bar->appendButton('Custom', $importButton, 'import');
					$exportButton = '<button class="btn btn-small" onclick="CKBox.open({handler:\'inline\',content: \'pagebuilderckparamsmessage\', fullscreen: false, size: {x: \'600px\', y: \'150px\'}});">
										<span class="icon-share"></span>
										' . JText::_('CK_EXPORT') . '
									</button>';
					$bar->appendButton('Custom', $exportButton, 'export');
					echo PagebuilderckHelper::showParamsMessage(false);
				}
			}
		}

		if ($canDo->get('core.edit.state')) {

			if (isset($this->items[0]->state)) {
				JToolBarHelper::divider();
			} else {
				//If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::trash('pages.delete');
			}



			if (isset($this->items[0]->state)) {
				JToolBarHelper::divider();
			}
		}

		//Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state)) {
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
				JToolBarHelper::divider();
				JToolBarHelper::trash('pages.delete', 'CK_DELETE');
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('pages.trash', 'CK_DELETE');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_pagebuilderck');
		}
	}
}
