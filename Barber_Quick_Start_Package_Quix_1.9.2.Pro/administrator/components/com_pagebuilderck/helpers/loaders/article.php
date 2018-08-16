<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Loader Class.
 */
class PagebuilderckLoaderArticles {

	private static $params;

	/*
	 * Get the items from the source
	 */
	public static function getItems($params) {
		if (empty(self::$params)) {
			self:$params = $params;
		}

		// load the content articles file
		$com_path = JPATH_SITE . '/components/com_content/';
		require_once $com_path . 'router.php';
		require_once $com_path . 'helpers/route.php';
		JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');

		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$articles->setState('params', $appParams);

		// Set the filters based on the module params
		$articles->setState('list.start', 0);
		if ($params->get('articleimgsource', 'introimage') == 'text') {
			$articles->setState('list.limit', $params->get('numberslides',0));
		} else {
			$articles->setState('list.limit', 0);
		}
		$articles->setState('filter.published', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$articles->setState('filter.access', $access);

		// Prep for Normal or Dynamic Modes
		$mode = $params->get('mode', 'normal');
		$option = $app->input->get('option', '', 'cmd');
		$view = $app->input->get('view', '', 'cmd');
		switch ($mode)
		{
			case 'dynamic':
				if ($option === 'com_content') {
					switch($view)
					{
						case 'category':
							$catids = array($app->input->get('id', 0, 'int'));
							break;
						case 'categories':
							$catids = array($app->input->get('id', 0, 'int'));
							break;
						case 'article':
							if ($params->get('show_on_article_page', 1)) {
								$article_id = $app->input->get('id', 0, 'int');
								$catid = $app->input->get('catid', 0, 'int');

								if (!$catid) {
									// Get an instance of the generic article model
									$article = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));

									$article->setState('params', $appParams);
									$article->setState('filter.published', 1);
									$article->setState('article.id', (int) $article_id);
									$item = $article->getItem();

									$catids = array($item->catid);
								}
								else {
									$catids = array($catid);
								}
							}
							else {
								// Return right away if show_on_article_page option is off
								return;
							}
							break;

						case 'featured':
						default:
							// Return right away if not on the category or article views
							return;
					}
				}
				else {
					// Return right away if not on a com_content page
					return;
				}

				break;

			case 'normal':
			default:
				$catids = $params->get('catid');
				$articles->setState('filter.category_id.include', (bool) $params->get('category_filtering_type', 1));
				break;
		}

		// Category filter
		if ($catids) {
			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
				// Get an instance of the generic categories model
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $appParams);
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();

				foreach($catids as $catid)
				{
					$categories->setState('filter.parentId', $catid);
					$recursive = true;
					$items = $categories->getItems($recursive);

					if ($items)
					{
						foreach($items as $category)
						{
							$condition = (($category->level - $categories->getParent()->level) <= $levels);
							if ($condition) {
								$additional_catids[] = $category->id;
							}

						}
					}
				}

				$catids = array_unique(array_merge($catids, $additional_catids));
			}

			$articles->setState('filter.category_id', $catids);
		}

		// Ordering
		$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
		$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));

		// New Parameters
		$articles->setState('filter.featured', $params->get('show_front', 'show'));
//		$articles->setState('filter.author_id', $params->get('created_by', ""));
//		$articles->setState('filter.author_id.include', $params->get('author_filtering_type', 1));
//		$articles->setState('filter.author_alias', $params->get('created_by_alias', ""));
//		$articles->setState('filter.author_alias.include', $params->get('author_alias_filtering_type', 1));
		$excluded_articles = $params->get('excluded_articles', '');

		// exclude by default the current article
		$option = $app->input->get('option', '', 'cmd');
		$view = $app->input->get('view', '', 'cmd');
		if ($option == 'com_content' && $view == 'article') {
			$excluded_articles .= ',' . $app->input->get('id', 0, 'int');
		}
		if ($excluded_articles) {
			// $excluded_articles = explode("\r\n", $excluded_articles);
			$excluded_articles = explode(",", $excluded_articles);
			$articles->setState('filter.article_id', $excluded_articles);
			$articles->setState('filter.article_id.include', false); // Exclude
		}

		$date_filtering = $params->get('date_filtering', 'off');
		if ($date_filtering !== 'off') {
			$articles->setState('filter.date_filtering', $date_filtering);
			$articles->setState('filter.date_field', $params->get('date_field', 'a.created'));
			$articles->setState('filter.start_date_range', $params->get('start_date_range', '1000-01-01 00:00:00'));
			$articles->setState('filter.end_date_range', $params->get('end_date_range', '9999-12-31 23:59:59'));
			$articles->setState('filter.relative_date', $params->get('relative_date', 30));
		}

		// Filter by language
		$articles->setState('filter.language', $app->getLanguageFilter());

		$items = $articles->getItems();

		// Display options
//		$show_date = $params->get('show_date', 0);
//		$show_date_field = $params->get('show_date_field', 'created');
//		$show_date_format = $params->get('show_date_format', 'Y-m-d H:i:s');
//		$show_category = $params->get('show_category', 0);
//		$show_hits = $params->get('show_hits', 0);
//		$show_author = $params->get('show_author', 0);
//		$show_introtext = $params->get('show_introtext', 0);
//		$introtext_limit = $params->get('text_limit', 100);

		// Find current Article ID if on an article page
//		if ($option === 'com_content' && $view === 'article') {
//			$active_article_id = $app->input->get('id', 0, 'int');
//		}
//		else {
//			$active_article_id = 0;
//		}

		// Prepare data for display using display options
		$slideItems = Array();
		foreach ($items as &$item)
		{
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;

			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			 else {
				// Angie Fixed Routing
				$app	= JFactory::getApplication();
				$menu	= $app->getMenu();
				$menuitems	= $menu->getItems('link', 'index.php?option=com_users&view=login');
				if(isset($menuitems[0])) {
					$Itemid = $menuitems[0]->id;
				} elseif ($app->input->get('Itemid', 0, 'int') > 0) { //use Itemid from requesting page only if there is no existing menu
					$Itemid = $app->input->get('Itemid', 0, 'int');
				}

				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid);
			}

			// Used for styling the active article
//			$item->active = $item->id == $active_article_id ? 'active' : '';

//			$item->displayDate = '';
//			if ($show_date) {
//				$item->displayDate = JHTML::_('date', $item->$show_date_field, $show_date_format);
//			}

//			if ($item->catid) {
//				$item->displayCategoryLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
//				$item->displayCategoryTitle = $show_category ? '<a href="'.$item->displayCategoryLink.'">'.$item->category_title.'</a>' : '';
//			}
//			else {
//				$item->displayCategoryTitle = $show_category ? $item->category_title : '';
//			}

//			$item->displayHits = $show_hits ? $item->hits : '';
//			$item->displayAuthorName = $show_author ? $item->author : '';
//			if ($show_introtext) {
//				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_articles_category.content');
//				$item->introtext = self::_cleanIntrotext($item->introtext);
//			}
//			$item->displayIntrotext = $show_introtext ? self::truncate($item->introtext, $introtext_limit) : '';
//			$item->displayReadmore = $item->alternative_readmore;

			// add the article to the slide
			$registry = new JRegistry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();
			$article_image = false;
			$slideItem_article_text = '';
			switch ($params->get('articleimgsource', 'introimage')) {
				case 'firstimage':
					$search_images = preg_match('/<img(.*?)src="(.*?)"(.*?)\/>/is', $item->introtext, $imgresult);
					$article_image = (isset($imgresult[2]) && $imgresult[2] != '') ? $imgresult[2] : false;
					$slideItem_article_text = (isset($imgresult[2])) ? str_replace($imgresult[0], '', $item->introtext) : $item->introtext;
					break;
				case 'fullimage':
					$article_image = (isset($item->images['image_fulltext']) && $item->images['image_fulltext']) ? $item->images['image_fulltext'] : false;
					$slideItem_article_text = $item->introtext;
					break;
				case 'introimage':
				default:
					$article_image = (isset($item->images['image_intro']) && $item->images['image_intro']) ? $item->images['image_intro'] : false;
					$slideItem_article_text = $item->introtext;
					break;
			}

			if ( ($article_image || $params->get('articleimgsource', 'introimage') == 'text')
					 && (count($slideItems) < (int) $params->get('numberslides', 0) || (int) $params->get('numberslides', 0) == 0)) {
				$slideItem = new stdClass();
				$slideItem->image = $article_image;
				$slideItem->images = $item->images;
				$slideItem->link = $item->link;
				$slideItem->title = $item->title;
				// $slideItem->text = JHTML::_('content.prepare', $slideItem_article_text);
				$slideItem->text = $slideItem_article_text;
				$slideItems[] = $slideItem;
			}
		}

		return $slideItems;
	}
}