<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.page-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Page controller class.
 */
class PagebuilderckControllerElement extends JControllerForm {

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save($key = null, $urlVar = null) {
		$app = JFactory::getApplication();
		if ($app->input->get('method','', 'cmd') == 'ajax') {
			// Check for request forgeries.
			JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		} else {
			// Check for request forgeries.
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		}
		
		$task = $this->getTask();

		// Initialise variables.
		
		$model = $this->getModel('Element', 'PagebuilderckModel');

		$appendToUrl = $app->input->get('tmpl') ? '&tmpl=' . $app->input->get('tmpl') : '';
		$layout = $app->input->get('layout') == 'modal' ? '&layout=modal' : '&layout=edit';

		// Get the user data.
		$data = $app->input->getArray($_POST);
		$data['htmlcode'] = '';

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
			$app->setUserState('com_pagebuilderck.edit.element.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_pagebuilderck.edit.element.id');
			$this->setRedirect('index.php?option=com_pagebuilderck&view=element'.$layout.'&id=' . $id . $appendToUrl, false);
			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_pagebuilderck.edit.element.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_pagebuilderck.edit.element.id');
			$app->enqueueMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect('index.php?option=com_pagebuilderck&view=element&task=element.edit'.$layout.'&id=' . $id . $appendToUrl, false);
			return false;
		}


		// Check in the profile.
		if ($return) {
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_pagebuilderck.edit.element.id', null);

		// Redirect to the list screen.
		$app->enqueueMessage(JText::_('Item saved successfully'));
		
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				// $this->holdEditId($context, $recordId);
				// $app->setUserState($context . '.data', null);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setRedirect('index.php?option=com_pagebuilderck&view=element&task=element.edit'.$layout.'&id=' . $return . $appendToUrl, false);
				break;
			default:
				// Clear the record id and data from the session.
				// $this->releaseEditId($context, $recordId);
				// $app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect('index.php?option=com_pagebuilderck&view=elements', false);
				break;
		}
		

		// Flush the data from the session.
		$app->setUserState('com_pagebuilderck.edit.element.data', null);
	}

	/**
	 * copy an existing element
	 * @return void
	 */
	function copy() {
		$model = $this->getModel();
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', '', 'array');
		$input->set('id', (int) $cid[0]);
		if (!$model->copy()) {
			$msg = JText::_('CK_COPY_ERROR');
			$type = 'error';
		} else {
			$msg = JText::_('CK_COPY_SUCCESS');
			$type = 'message';
		}

		$this->setRedirect('index.php?option=com_pagebuilderck&view=elements', $msg, $type);
	}

	/**
	 * Loads the import method
	 *
	 * @return void
	 */
	// public function import() {
		// if ($importClass = PagebuilderckHelper::getParams('import')) {
			// $importClass->test();
			
		// }
	// }

	/**
	 * Loads the export method
	 *
	 * @return void
	 */
	// public function export() {
	// $cid = $input->get('cid', '', 'array');
	// var_dump($cid);
		// if ($exportClass = PagebuilderckHelper::getParams('export')) {
			// $exportClass->test();
		// }
	// }
}