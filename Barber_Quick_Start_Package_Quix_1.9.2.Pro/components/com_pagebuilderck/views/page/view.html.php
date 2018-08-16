<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class PagebuilderckViewPage extends JViewLegacy
{
	protected $item;

	// protected $params;

	// protected $print;

	protected $state;

	// protected $user;

	public function display($tpl = null)
	{
		$app	= JFactory::getApplication();
		$user = JFactory::getUser();

		if (substr( $app->input->get('layout', null, 'cmd'), 0, 4 ) !== 'ajax') {
			$this->item		= $this->get('Item');
			$this->state	= $this->get('State');
			// $this->form = $this->get('Form');

			// check that we got a page
			if (empty($this->item))
			{
				return JError::raiseError(404, JText::_('COM_PAGEBUILDERCK_ERROR_PAGE_NOT_FOUND'));
			}
		}

		// check if we are viewing the frontend layout
		if ( ($app->input->get('layout', null, 'cmd') === null || $app->input->get('layout', null, 'cmd') === 'default')
			 && $tpl === null) {

			// check the rights to access the page
			$groups	= $user->getAuthorisedViewLevels();
			// if ((!in_array($this->item->access, $groups)) || (!in_array($this->item->category_access, $groups)))
			if (!in_array($this->item->access, $groups))
			{
				JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
				return;
			}
		} else {
			// check that the user has the rights to edit
			$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));
			if ($authorised !== true)
			{
				JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
				return false;
			}

			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

			// get instance of the editor to load the css / js in the page
			$this->ckeditor = PagebuilderckHelper::loadEditor();

			// check if the page is available for modification
			$model = $this->getModel();
			$id = $app->input->get('id', 0, 'int');
			if (! $model->checkout($id)) {
				// JError::raiseWarning(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
				// $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
				// $this->setMessage($this->getError(), 'error');
				// $app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()), 'error');
				$app->redirect(
				JRoute::_(
						'index.php?option=com_pagebuilderck&view=page&id=' . $id, false
					)
				);
				return false;
			}
		}

		// Get the parameters
		// $params = JComponentHelper::getParams('com_pagebuilderck');
		// if ($app->input->get('layout', '', 'cmd') !== null || $tpl !== null) {

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		// loads the neede library
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
		// Check if access is not public
		// $groups	= $user->getAuthorisedViewLevels();

		// if ($this->_layout == 'edit' || !$this->item->id) {
			// $authorised = $user->authorise('core.edit.own', 'com_pagebuilderck');

			// if ($authorised !== true) {
				// Redirect to the edit screen.
				// $app->redirect(JURI::root() . 'index.php?option=com_templateck&view=login&template=templatecreatorck&tmpl=login&id=' . $this->item->id);
				// return false;
			// }
		// }
// TODO : ajouter permissions et acl
		// if ((!in_array($item->access, $groups)) || (!in_array($item->category_access, $groups)))
		// {
			// JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			// return;
		// }

		// $model = $this->getModel();
		// $model->hit();

		return parent::display($tpl);
	}
}
