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

jimport('joomla.application.component.controller');

/**
 * Class QuixController
 *
 * @since  1.6
 */
class QuixController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$cachable = true;
		require_once JPATH_COMPONENT . '/helpers/quix.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quix.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'page');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}

	/**
	 * Method to perform ajax call
	 *
	 * @param   element  element name must be passed
	 * @param   format   format can be html, raw, json; default: json
	 * @param   method   default method: get; will call getAjax(). postfix is: Ajax. 
	 * 
	 * @return  core output, like: json or other format
	 * url example: index.php?option=com_quix&task=ajax&element=simple-contact&format=json&method=get
	 * @since    1.3
	 */
	public function ajax()
	{
		// Reference global application object
		$app = JFactory::getApplication();

		// JInput object
		$input = $app->input;

		// Requested format passed via URL
		$format = strtolower($input->getWord('format', 'json'));
		
		// Requested element name
		$element = strtolower($input->get('element', '', 'string'));

		// Check for valid element
		if (!$element)
		{
			$results = new InvalidArgumentException(JText::_('COM_QUIX_SPECIFY_ELEMENT'), 404);
		}

		// Now we have element name, load the class
		else
		{
			// check if element found 
			$elementFound = false;

			// load element
			// first check if its from default template
			if ( is_file( QUIX_TEMPLATE_PATH . "/elements/$element/helper.php" ) )
			{
		       $elementFound = true;
		       $element_path = QUIX_TEMPLATE_PATH . "/elements";
		       require_once QUIX_TEMPLATE_PATH . "/elements/$element/helper.php";
		   	}
		   	// check  if its from core then
		   	elseif ( is_file( QUIX_PATH . "/app/elements/$element/helper.php" ) )
		   	{
		   		$elementFound = true;
		   		$element_path = QUIX_PATH . "/app/elements";
		   		require_once QUIX_PATH . "/app/elements/$element/helper.php";
		   	}

		   	if( !$elementFound )
		   	{
		   		$results = new InvalidArgumentException(JText::_('COM_QUIX_SPECIFY_ELEMENT'), 404);
		   	}
		   	else
		   	{
		   		///////////////////////////////////////////////////
			    //
			    // Generate class name from element name
			    //
			    //////////////////////////////////////////////////
			    $className = str_replace('-', ' ', $element);

			    $className = ucwords($className);

			    $className = str_replace(' ', '', $className);

			    $elementClassName = "Quix{$className}Element";

			    // Get the method name
			    $method = $input->get('method') ? $input->get('method') : 'get';

			    ///////////////////////////////////////////////////
			    //
			    // Dynamically calling user required method of
			    // the element for ajax.
			    //
			    //////////////////////////////////////////////////
			    // first check method exist
			    if( method_exists($elementClassName, $method . 'Ajax') )
			    {
			  		// Load language file for module
					$elementlang = str_replace('-', '_', $element);
					$lang     = JFactory::getLanguage();
					$lang->load('quix_' . $elementlang, $element_path . "/$element", null, false, true);

			    	try
					{
						$results = call_user_func($elementClassName . '::' . $method . 'Ajax');
					}
					catch (Exception $e)
					{
						$results = $e;
					}

			    }
			    // Method does not exist
				else
				{
					$results = new LogicException(JText::sprintf('COM_QUIX_METHOD_NOT_EXISTS', $method . 'Ajax'), 404);
				}

		   	}
		}

		// Return the results in the desired format
		switch ($format)
		{
			// JSONinzed
			case 'json' :
				echo new JResponseJson($results, null, false, $input->get('ignoreMessages', true, 'bool'));

				break;

			// Handle as raw format
			default :
				// Output exception
				if ($results instanceof Exception)
				{
					// Log an error
					JLog::add($results->getMessage(), JLog::ERROR);

					// Set status header code
					$app->setHeader('status', $results->getCode(), true);

					// Echo exception type and message
					$out = get_class($results) . ': ' . $results->getMessage();
				}
				// Output string/ null
				elseif (is_scalar($results))
				{
					$out = (string) $results;
				}
				// Output array/ object
				else
				{
					$out = implode((array) $results);
				}

				echo $out;

				break;
		}
		
		$app->close();
	}
}
