<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * Controller for single page view
 *
 * @since  1.5.19
 */
class PagebuilderckControllerPage extends JControllerForm
{

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'page';

	/**
	 * The URL option for the component.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $option = 'com_pagebuilderck';
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6.4
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   12.2
	 */
	// public function edit($key = 'id', $urlVar = null) {
		// parent:edit($key, $urlVar = null);
	// }

	/**
	 * Method to save data.
	 *
	 * @return	void
	 */
	public function save($key = null, $urlVar = null) {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$task = $this->getTask();

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Page', 'PagebuilderckModel');

		// Get the user data.
		$data = $app->input->getArray($_POST);

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_pagebuilderck.edit.page.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_pagebuilderck.edit.page.id');
			$this->setRedirect('index.php?option=com_pagebuilderck&view=page&layout=edit&id=' . $id, false);
			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_pagebuilderck.edit.page.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_pagebuilderck.edit.page.id');
			$app->enqueueMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect('index.php?option=com_pagebuilderck&view=page&layout=edit&id=' . $id, false);
			return false;
		}


		// Check in the profile.
		if ($return) {
			$model->checkin($return);
		}

		// set the new ID
		$data['id'] = $return;

		// Clear the profile id from the session.
		$app->setUserState('com_pagebuilderck.edit.page.id', null);

		// Redirect to the list screen.
		$app->enqueueMessage(JText::_('Item saved successfully'));

		$appendUrl = $app->input->get('layout') == 'modal' ? '&layout=modal' : '';
		$appendUrl .= $app->input->get('tmpl') == 'component' ? '&tmpl=component' : '';

		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				// $this->holdEditId($context, $recordId);
				// $app->setUserState($context . '.data', null);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setRedirect('index.php?option=com_pagebuilderck&view=page&layout=edit&id=' . $data['id'] . $appendUrl, false);
				break;
			default:
				// Clear the record id and data from the session.
				// $this->releaseEditId($context, $recordId);
				// $app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect('index.php?option=com_pagebuilderck&view=page&id=' . $data['id'] . $appendUrl, false);
				break;
		}
		

		// Flush the data from the session.
		$app->setUserState('com_pagebuilderck.edit.page.data', null);
	}

	function cancel($key = 'id') {
		// JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Redirect back to list
		// $this->setRedirect('index.php?option=com_pagebuilderck&view=page&id=' . JFactory::getApplication()->input->get('id'), false);
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";

		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		$recordId = $app->input->getInt($key);

		// Attempt to check-in the current record.
		if ($recordId)
		{
			if ($checkin)
			{
				if ($model->checkin($recordId) === false)
				{
					// Check-in failed, go back to the record and display a notice.
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
					$this->setMessage($this->getError(), 'error');

					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_item
							. $this->getRedirectToItemAppend($recordId, $key), false
						)
					);

					return false;
				}
			}
		}

		// Clean the session data and redirect.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->setRedirect(
			JRoute::_(
				'index.php?option=com_pagebuilderck&view=page&id=' . $recordId, false
			)
		);

		return true;
	}
}
