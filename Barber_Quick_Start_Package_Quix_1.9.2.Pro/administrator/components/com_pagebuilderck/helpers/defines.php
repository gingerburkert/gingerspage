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

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}

if (!defined('PAGEBUILDERCK_MEDIA_PATH'))
{
	define('PAGEBUILDERCK_MEDIA_PATH', JPATH_ROOT . '/media/com_pagebuilderck');
}
define('PAGEBUILDERCK_ADMIN_URL', JUri::root(true) . '/administrator/index.php?option=com_pagebuilderck');
