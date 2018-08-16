<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * pages Component Message Model
 *
 * @since  1.6
 */
class QuixControllerGet extends JControllerLegacy
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function hasImage()
	{
		// Reference global application object
		$app = JFactory::getApplication();

		// JInput object
		$input = $app->input;

		// Requested format passed via URL
		$format = strtolower($input->getWord('format', 'json'));

		// Requested element name
		$path = strtolower($input->get('path', '', 'string'));

		// check if path passed
		if( !$path )
	   	{
	   		$results = new InvalidArgumentException(JText::_('COM_QUIX_NO_ARGUMENT'), 403);
	   	}

		// first check if its from default template
		if ( is_file( PATH_ROOT . $path ) )
		{
			$results = true;
		}
		else
		{
			$results = new InvalidArgumentException(JText::_('COM_QUIX_FILE_NOT_EXISTS'), 404);
		}

		// return result
		echo new JResponseJson($results , null, false, $input->get('ignoreMessages', true, 'bool'));

		$app->close();
	}
}
