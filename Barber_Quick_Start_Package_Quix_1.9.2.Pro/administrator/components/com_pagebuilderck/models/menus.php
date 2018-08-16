<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class PagebuilderckModelMenus extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 * @see        JController
	 * @since    1.6
	 */
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'state', 'a.state',
				'published', 'a.state'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_pagebuilderck');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '') {
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/*public function getItems() {
		JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_menus/models', 'MenusModel');
		// Get an instance of the generic menus model
		$items = JModelLegacy::getInstance('Items', 'MenusModel', array('ignore_request' => true));
		$items->setState('filter.level', '1');
		$items->setState('filter.menutype', 'test');
////		var_dump($items->getItems());die;
		return $items;
	}*/

	public function getChildrenItems($menutype, $parentId) {
		JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_menus/models', 'MenusModel');
		// Get an instance of the generic menus model
		$items = JModelLegacy::getInstance('Items', 'MenusModel', array('ignore_request' => true));
		if (! $parentId) $items->setState('filter.level', '1');
		$items->setState('filter.menutype', $menutype);
		$items->setState('filter.parent_id', $parentId);

		return $items->getItems();
	}

	public function getMenus() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select($db->qn(array('menutype', 'title')))
					->from($db->qn('#__menu_types'));
//					->where($db->qn('menutype') . ' = ' . $db->q($menuType));

		$menus = $db->setQuery($query)->loadObjectList();
		return $menus;
	}
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery() {
		
	}
}
