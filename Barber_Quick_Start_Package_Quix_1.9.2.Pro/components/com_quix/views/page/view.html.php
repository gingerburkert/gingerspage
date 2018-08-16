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

// jimport('joomla.application.component.view');
use Joomla\Registry\Registry;
/**
 * View to edit
 *
 * @since  1.6
 */
class QuixViewPage extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	protected $config;

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
		$this->params = $this->state->get('params');
		$this->config = JComponentHelper::getComponent('com_quix')->params;
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}
		
		// Check the view access to the article (the model has already computed the values).
		if ($this->item->params->get('access-view') == false && ($this->item->params->get('show_noauth', '0') == '0'))
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return;
		}
		
		if(isset($this->item->id) && $this->item->id)
		{
			// count hits
			$this->get('Hit');
			
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

			// trigger content plugin
			$this->item->text = $page;
			
			// $offset = $this->state->get('list.offset');
			// JPluginHelper::importPlugin('content');
			// $dispatcher->trigger('onContentPrepare', array ('com_quix.page', &$this->item, &$this->item->params, $offset));
		}
		elseif(isset($_POST) && isset($_POST['layout']) )
		{	
			// load common assets
		    quixRenderhead();

			// render quix content
			$page = quixRenderItem($_POST['layout']);

			// load output assets
		    Assets::load();

		    // load quixtrap from system plugin
		    plgSystemQuix::addQuixTrapCSS();

			$this->item->id = 0;
			$this->item->text = $page;
			// $offset = $this->state->get('list.offset');
			// JPluginHelper::importPlugin('content');
			// $dispatcher->trigger('onContentPrepare', array ('com_quix.page', &$this->item, &$this->item->params, $offset));
		}

		//add custom code to jdoc
		$registry = new Registry;
		$params = $registry->loadString($this->item->params);
		$code = $params->get('code', '');
		if($code){
			$this->document->addCustomTag($code);
		}
		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// We need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_QUIX_DEFAULT_PAGE_TITLE'));
		}

		//get page title
		$title = $this->params->get('page_title', '');
		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		// add opengraph meta
		$registry = new Registry;
        if( !method_exists($registry, 'loadString') ) return;

		$this->metadata = $registry->loadString($this->item->metadata);
		
		$this->meta_title = $this->metadata->get('title', $title);

		$description = $this->params->get('page_description', '');
		$this->meta_desc = $this->metadata->get('desc', $description);

		$addog = $this->metadata->get('addog');
		$addtw = $this->metadata->get('addtw');
		
		if($addog) $this->addOpenGraph();		
		if($addtw) $this->addTwitterCard();

		if($this->config->get('generator_meta', 1) && !QuixHelper::isFreeQuix()){
			$this->document->setMetadata('application-name', 'Quix Page Builder');		
		}
	}

	public function addOpenGraph()
	{
		$app  = JFactory::getApplication();
		$this->document->setMetadata('og:type', 'website');
		$this->document->setMetadata('og:site_name', $app->get('sitename'));
		$this->document->setMetadata('og:title', $this->meta_title);			
		$this->document->setMetadata('og:description', $this->meta_desc);

		$this->document->setMetadata('og:url', JURI::current());
		$this->document->setMetadata('fb:app_id', $this->metadata->get('fb_appid', ''));

		return true;
	}

	public function addTwitterCard()
	{
		$this->document->setMetadata('twitter:card', 'summary');
		$this->document->setMetadata('twitter:site', $this->metadata->get('twitter_username', ''));
		$this->document->setMetadata('twitter:title', $this->meta_title);
		$this->document->setMetadata('twitter:description', $this->meta_desc);

		return true;
	}
}
