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

/**
 * View to edit
 *
 * @since  1.6
 */
class QuixViewCollection extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();
		$dispatcher = JEventDispatcher::getInstance();
		
		$this->state  = $this->get('State');
		$this->item   = $this->get('Data');
		$this->params = $app->getParams('com_quix');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		if(isset($this->item->id) && $this->item->id)
		{
			// reset after loaded
		    Assets::resetObject();
			
			// load common assets
		    quixRenderhead();

			// render quix content
			$page = quixRenderItem($this->item);

			// load output assets
		    Assets::load();

		    // load quixtrap from system plugin
		    plgSystemQuix::addQuixTrapCSS();
			
			$this->item->text = $page;
			// $offset = $this->state->get('list.offset');
			// JPluginHelper::importPlugin('content');
			// $dispatcher->trigger('onContentPrepare', array ('com_quix.collection', &$this->item, &$this->item->params, $offset));
		}

		// Increment the hit counter of the product.
		// $model = $this->getModel();
		// $model->hit();
		
		parent::display($tpl);
	}

}
