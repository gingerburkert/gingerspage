<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Quix model.
 *
 * @since  1.6
 */
class QuixModelPage extends JModelAdmin
{
	/**
	 * @var      string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $item;
	protected $text_prefix = 'COM_QUIX';

	/**
	 * The context used for the associations table
	 *
	 * @var      string
	 * @since    3.4.4
	 */
	protected $associationsContext = 'com_quix.item';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 *
	 * @since    1.6
	 */
	public function getTable($type = 'Page', $prefix = 'QuixTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm(
			'com_quix.page', 'page',
			array('control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('p_id'))
		{
			$id = $jinput->get('p_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}

		// Determine correct permissions to check.
		if ($this->getState('page.id'))
		{
			$id = $this->getState('page.id');

			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Existing record. Can only edit own articles in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing article.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_quix.article.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_quix')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with article language and category when editing existing article with associations
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		// Check if article is associated
		if ($this->getState('page.id') && $app->isSite() && $assoc)
		{
			$associations = JLanguageAssociations::getAssociations('com_quix', '#__quix', 'com_quix.item', $id);

			// Make fields read only
			if (!empty($associations))
			{
				$form->setFieldAttribute('language', 'readonly', 'true');
				$form->setFieldAttribute('catid', 'readonly', 'true');
				$form->setFieldAttribute('language', 'filter', 'unset');
				$form->setFieldAttribute('catid', 'filter', 'unset');
			}
		}

		return $form;
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   JForm   $form   The form object
	 * @param   array   $data   The data to be merged into the form object
	 * @param   string  $group  The plugin group to be executed
	 *
	 * @return  void
	 *
	 * @since    3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		// // Association content items
		// $app = JFactory::getApplication();
		// $assoc = JLanguageAssociations::isEnabled();

		// if ($assoc)
		// {
		// 	$languages = JLanguageHelper::getLanguages('lang_code');
		// 	$addform = new SimpleXMLElement('<form />');
		// 	$fields = $addform->addChild('fields');
		// 	$fields->addAttribute('name', 'associations');
		// 	$fieldset = $fields->addChild('fieldset');
		// 	$fieldset->addAttribute('name', 'item_associations');
		// 	$fieldset->addAttribute('description', 'COM_QUIX_ITEM_ASSOCIATIONS_FIELDSET_DESC');
		// 	$add = false;

		// 	foreach ($languages as $tag => $language)
		// 	{
		// 		if (empty($data->language) || $tag != $data->language)
		// 		{
		// 			$add = true;
		// 			$field = $fieldset->addChild('field');
		// 			$field->addAttribute('name', $tag);
		// 			$field->addAttribute('type', 'modal_page');
		// 			$field->addAttribute('language', $tag);
		// 			$field->addAttribute('label', $language->title);
		// 			$field->addAttribute('translate_label', 'false');
		// 			$field->addAttribute('edit', 'true');
		// 			$field->addAttribute('clear', 'true');
		// 		}
		// 	}

		// 	if ($add)
		// 	{
		// 		$form->load($addform, false);
		// 	}
		// }

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return   mixed  The data for the form.
	 *
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_quix.edit.page.data', array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;

			//Support for multiple or not foreign key field: access
			$array = array();
			foreach((array)$data->access as $value):
				if(!is_array($value)):
					$array[] = $value;
				endif;
			endforeach;
			$data->access = $array;
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the metadata field to an array.
			$registry = new Registry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

		}

		// Load associated content items
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();
		
		// if ($assoc)
		// {
		// 	$item->associations = array();

		// 	if ($item->id != null)
		// 	{
		// 		$associations = JLanguageAssociations::getAssociations('com_quix', '#__quix', 'com_quix.item', $item->id);

		// 		foreach ($associations as $tag => $association)
		// 		{
		// 			$item->associations[$tag] = $association->id;
		// 		}
		// 	}
		// }

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  Table Object
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__quix');
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		// echo 4;die;
		// print_r($data);die;
		$input = JFactory::getApplication()->input;
		$filter  = JFilterInput::getInstance();

		if (isset($data['metadata']))
		{
			$registry = new Registry;
			$registry->loadArray($data['metadata']);
			$data['metadata'] = (string) $registry;
		}
		
		if (isset($data['params']))
		{
			if(isset($data['params']['reset_counter']) && $data['params']['reset_counter']){
				$data['hits'] = 0;
				$data['params']['reset_counter'] = 0;
			}

			// Make sure EOL is Unix
			$data['params']['code'] = str_replace(array("\r\n", "\r"), "\n", $data['params']['code']);

			$registry = new Registry;
			$registry->loadArray($data['params']);
			$data['params'] = (string) $registry;
		}

		// Clear relavent cache
		$this->cleanCache();

		return parent::save($data);

	}

	/**
	 * Method to duplicate modules.
	 *
	 * @param   array  &$pks  An array of primary key IDs.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @since   1.0.0
	 * @throws  Exception
	 */
	public function duplicate(&$pks)
	{
		$user	= JFactory::getUser();
		$db		= $this->getDbo();

		// Access checks.
		if (!$user->authorise('core.create', 'com_quix.page'))
		{
			throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		foreach ($pks as $pk)
		{
			$data = (array) $this->getItem($pk);
			$name = $this->NewTitle($data['title']);

			$data['id']	= '';
			$data['title']	= $name;
			$data['state']	= 0;

			if(!$this->save($data)){
				return false;
			}
		}

		// Clear modules cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to change the name & alias.
	 *
	 * @param   integer  $category_id  The id of the parent.
	 * @param   string   $alias        The alias.
	 * @param   string   $name         The name.
	 *
	 * @return  array  Contains the modified name and alias.
	 *
	 * @since   3.1
	 */
	public function NewTitle($name)
	{
		// Alter the name
		$name = JString::increment($name);

		return $name;
	}
}
