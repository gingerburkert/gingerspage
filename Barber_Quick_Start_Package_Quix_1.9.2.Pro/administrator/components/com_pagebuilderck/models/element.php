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

use Joomla\Registry\Registry;
// jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modeladmin');
jimport('joomla.event.dispatcher');
jimport('joomla.filesystem.folder');

class PagebuilderckModelElement extends JModelAdmin {

	var $_item = null;

	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	// protected $text_prefix = 'COM_PAGEBUILDERCK';

	/**
	 * The type alias for this content type (for example, 'com_content.article').
	 *
	 * @var      string
	 * @since    3.2
	 */
	// public $typeAlias = 'com_pagebuilderck.page';
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState() {
		$app = JFactory::getApplication('com_pagebuilderck');

		// Load state from the request userState on edit or from the passed variable on default
		if ($app->input->get('layout', '', 'cmd') == 'edit') {
			$id = JFactory::getApplication()->getUserState('com_pagebuilderck.edit.element.id');
		} else {
			$id = $app->input->get('id', 0 , 'int');
			JFactory::getApplication()->setUserState('com_pagebuilderck.edit.element.id', $id);
		}
		$this->setState('element.id', $id);

		// Load the parameters.
		// $params = $app->getParams();
		// $this->setState('params', $params);
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null) {
		$app = JFactory::getApplication();
		if ($this->_item === null) {
			$this->_item = false;

			if (empty($id)) {
				$id = $app->input->get('id', '', 'int');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id)) {
				// Check published state.
				if ($published = $this->getState('filter.published')) {
					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}

		// transform params to JRegistry object
		// if (isset($this->_item->params)) $this->_item->params = new JRegistry($this->_item->params);

		$this->_item->htmlcode = str_replace("|URIROOT|", JUri::root(true), $this->_item->htmlcode);
		return $this->_item;
	}

	/**
	* Return ony the html code from the item
	*/
	public function getHtml($id) {
		if (! $id) return '';
		$data = $this->getData($id);
		return isset($data->htmlcode) ? $data->htmlcode : '';
	}

	public function getTable($type = 'Element', $prefix = 'PagebuilderckTable', $config = array()) {
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_pagebuilderck.element', 'element', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() {
		$data = $this->getData();

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data) {
		$input = JFactory::getApplication()->input;
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('element.id');
		$user = JFactory::getUser();
		// $data['htmlcode'] = JRequest::getVar('htmlcode', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['htmlcode'] = $data['htmlcode'] ? $data['htmlcode'] : $input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(JUri::root(true), "|URIROOT|", $data['htmlcode']);
		
		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new Registry;
			$registry->loadArray($data['options']);
			$data['params'] = (string) $registry;
		}

		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'element.' . $id);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_pagebuilderck');
		}

		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		$table = $this->getTable();
		$table->load($data['id']);

		// make a backup before save
		PagebuilderckHelper::makeBackup($this->getData(), 'myelements');

		if ($table->save($data) === true) {
			return $table->id;
		} else {
			return false;
		}
	}

	/**
	 * Method to copy a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function copy() {

		$row = $this->getTable();
		$cid = JFactory::getApplication()->input->get('id', '', 'array');
		$pk = isset($cid[0]) ? (int) $cid[0] : null;
		$data = $this->getItem($pk);
		$data->id = 0;

		// give the new name
		$data->title .= '(copy)';
		
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($row->getErrorMsg());
			return false;
		}

		// $this->setId($row->id);

		return true;
	}

	public function getElements() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select', 'a.*'
				)
		);
		$query->from('`#__pagebuilderck_elements` AS a');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' .$search . '%');
				$query->where('(' . 'a.title LIKE ' . $search . ' )');
			}
		}

		// Do not list the trashed items
		$query->where('a.state > -1');

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn) {
			$query->order($orderCol . ' ' . $orderDirn);
		}

		$elements = $db->setQuery($query)->loadObjectList();

		return $elements;
	}

}