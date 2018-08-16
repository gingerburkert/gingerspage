<?php
/**
 * @copyright	Copyright (C) 2016 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

// include the content search plugin to use the search in article for tags
include_once JPATH_ROOT . '/plugins/search/content/content.php';
// loads the helper in any case
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

/**
 * Page Builder CK search plugin.
 *
 */
class PlgSearchPagebuildercksearch extends PlgSearchContent
{

	public function onContentSearchAreas()
	{
//		static $areas = array(
//			'content' => 'JGLOBAL_ARTICLES'
//		);
//
//		return $areas;
	}
	/**
	 * Search content (pages).
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		// load the articles from com_content
		$articles = @parent::onContentSearch($text, $phrase, $ordering, $areas);

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();

		$searchText = $text;
		

		// look for tags to remove before display
		$searchRegex = '#<style[^>]*>.*?</style>#si';
		foreach ($articles as &$article)
		{
			$article->text = preg_replace($searchRegex, '', $article->text);
		}

		$limit = $this->params->def('search_limit', 50);

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		switch ($phrase)
		{
			case 'exact':
				$text = $db->quote('%' . $db->escape($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'a.title LIKE ' . $text;
				$wheres2[] = 'a.htmlcode LIKE ' . $text;
				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word = $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 = array();
//					$wheres2[] = 'LOWER(a.title) LIKE LOWER(' . $word . ')';
					$wheres2[] = 'LOWER(a.htmlcode) LIKE LOWER(' . $word . ')';
					$wheres[] = implode(' OR ', $wheres2);
				}

				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 'a.created ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
				$order = 'a.title ASC';
				break;

			case 'newest':
			default:
				$order = 'a.created DESC';
				break;
		}

		$rows = array();
		$query = $db->getQuery(true);

		// Search pages.
		if ($limit > 0)
		{
			$query->clear();
			$query->select('a.id, a.title AS title, a.created AS created, a.htmlcode as text')

				->from('#__pagebuilderck_pages AS a')
				->where(
					'(' . $where . ') AND a.state=1 '
				)
				->group('a.id, a.title, a.created')
				->order($order);

			$db->setQuery($query, 0, $limit);
			try
			{
				$list = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				$list = array();
				JFactory::getApplication()->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			}
			$limit -= count($list);

			$ispbck = true;
			if (isset($list))
			{
				foreach ($list as $key => $item)
				{
					// $searcContentPlugin = new PlgSearchContent();
					$results = @parent::onContentSearch('{pagebuilderck ' . $list[$key]->id . '}', $phrase, $ordering, $areas);

					if (count($results)) {
						$ispbck = false;
						$rows[] = $results;
						foreach ($results as $key2 => $item2)
						{
							$list[$key2]->href = ContentHelperRoute::getArticleRoute($item2->slug, $item2->catid, $item2->language);
						}
					} else {
						$list[$key]->href = 'index.php?option=com_pagebuilderck&view=page&id=' . $list[$key]->id;
						$list[$key]->section = '';
						$list[$key]->browsernav = '2';
					}
				}
			}

			if ($ispbck == true) {
				$rows[] = $list;
			}
		}

		$results = array();

//		$searchRegex2 = '#</div>.*?<div>#si';
		if (count($rows))
		{
			foreach ($rows as $row)
			{
				$new_row = array();

				foreach ($row as $page)
				{
					
					
					$page->text = preg_replace($searchRegex, '', $page->text);
//					$page->text = preg_replace($searchRegex2, '', $page->text);
					$page->text = trim($page->text);
					// if (SearchHelper::checkNoHtml($page, $searchText, array('htmlcode', 'title')))
					// {
						$new_row[] = $page;
					// }
				}
				$results = array_merge($results, (array) $new_row);
				$results = array_merge($results, (array) $articles);
			}
		}

		return $results;
	}
}
