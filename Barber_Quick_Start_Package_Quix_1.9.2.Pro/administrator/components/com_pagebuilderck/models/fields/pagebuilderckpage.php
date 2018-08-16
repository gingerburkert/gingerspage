<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPagebuilderckpage extends JFormField
{

	protected $type = 'Pagebuilderckpage';

	protected function getInput() {
		// Prepare HTML code
		$html = array();

		// Compute attributes for the grouped list
		$attr = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Compute the current selected values
		$selected = array($this->value);

		$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	
		return implode($html);
	}
	
	protected function getOptions()
	{
		$options = array();
		
		// Get the database object and a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('title, id');
		$query->from('#__pagebuilderck_pages as a');
		$query->where('a.state = 1');
		// $query->where('a.client_id = ' . (int) $clientId);

		// Set the query and load the templates.
		$db->setQuery($query);
		$pages = $db->loadObjectList('id');

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		foreach ($pages as $page)
		{
			$value = (string) $page->id;
			$text = (string) $page->title;

			$tmp = JHtml::_(
				'select.option', $value,
				$text, 'value', 'text'
			);

			// Add the option object to the result set.
			$options[] = $tmp;

		}

		reset($options);

		return $options;
	}
}