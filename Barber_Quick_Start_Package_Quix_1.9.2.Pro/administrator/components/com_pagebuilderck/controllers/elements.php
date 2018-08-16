<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Pages list controller class.
 */
class PagebuilderckControllerElements extends JControllerAdmin {

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'element', $prefix = 'PagebuilderckModel', $config = array()) {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function import() {
		$app = JFactory::getApplication();
		if ($importClass = PagebuilderckHelper::getParams('import')) {
			$importClass->importFile();
		} else {
			$msg = JText::_('CK_PAGEBUILDERCK_PARAMS_NOT_FOUND');
			$app->redirect("index.php?option=com_pagebuilderck&view=elements", $msg, 'error');
			return false;
		}
	}

	public function export() {
		$app = JFactory::getApplication();
		if ($exportClass = PagebuilderckHelper::getParams('export')) {
			$exportClass->exportFile();
		} else {
			$msg = JText::_('CK_PAGEBUILDERCK_PARAMS_NOT_FOUND');
			$app->redirect("index.php?option=com_pagebuilderck&view=elements", $msg, 'error');
			return false;
		}
	}

	public function order() {
		// security check
		// if (! PagebuilderckHelper::getAjaxToken()) {
			// exit();
		// }
		$app = JFactory::getApplication();
		$ordering = $app->input->get('ordering', '', 'array');
		$model = $this->getModel('elements');
		$result = $model->saveOrder($ordering);
		echo (int) $result;
		exit;
	}
}