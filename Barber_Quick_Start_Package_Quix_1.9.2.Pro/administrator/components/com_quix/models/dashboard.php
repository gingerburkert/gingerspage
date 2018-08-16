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
 * Message configuration model.
 *
 * @since  1.6
 */
class QuixModelDashboard extends JModelForm
{
	public function generateState()
	{
		$this->populateState();
	}
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$db = $this->getDbo();
		// get extensionid
		$query = $db->getQuery(true)
			->select('extension_id')
			->from('#__extensions')
			->where($db->quoteName('type') . ' = ' . $db->quote('package'))
			->where($db->quoteName('element') . ' = ' . $db->quote('pkg_quix'));

		$db->setQuery($query);
		
		$extensionid = $db->loadResult();
		$this->setState('extensionid', $extensionid);

		// get update_site_id
		$query = $db->getQuery(true)
			->select('update_site_id')
			->from('#__update_sites_extensions')
			->where($db->quoteName('extension_id') . ' = ' . $db->quote($extensionid));

		$db->setQuery($query);
		
		$update_site_id = $db->loadResult();
		$this->setState('update_site_id', $update_site_id);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_quix');
		$this->setState('params', $params);
	}

	/**
	 * Method to get a single record.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function &getItem()
	{
		$item = new JObject;

		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->quoteName('#__update_sites', 'a'))
			->where($db->quoteName('a.update_site_id') . ' = ' . (int) $this->getState('update_site_id'));

		$db->setQuery($query);

		try
		{
			$row = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}
		if( isset($row->extra_query) )
		{
			$extra_query 		= $row->extra_query;
			if(!empty($extra_query))
			{
				$extra_queryArray 	= explode("&", $extra_query);
				if(is_array($extra_queryArray))
				{
					foreach ($extra_queryArray as $key => $value) 
					{
						$valuearray = explode("=", $value);
						$item->set($valuearray[0], $valuearray[1]);
					}
				}
			}
		}

		$this->preprocessData('com_quix.config', $item);

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm	 A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_quix.config', 'config', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
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
		$db = $this->getDbo();

		if ($extensionid = (int) $this->getState('extensionid'))
		{
			
			if (count($data))
			{
				
				// print_r($data);die;
				$prefix = '';
				$extra_query = '';
				foreach ($data as $key => $value) {
					$extra_query .= $prefix . $key . '=' . $value;
					$prefix = '&';
				}
				
				$query = $db->getQuery(true);

				// Fields to update.
				$fields = array(
				    $db->quoteName('extra_query') . ' = ' . $db->quote($extra_query)
				);
				 
				// Conditions for which records should be updated.
				$conditions = array(
				    $db->quoteName('update_site_id') . ' = ' . $db->quote((int) $this->getState('update_site_id'))
				);
				 
				$query->update($db->quoteName('#__update_sites'))->set($fields)->where($conditions);

				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					$this->setError($e->getMessage());

					return false;
				}
			}

			return true;
		}
		else
		{
			$this->setError('COM_QUIX_ERR_INVALID_UPDATE_INFO');

			return false;
		}
	}
}
