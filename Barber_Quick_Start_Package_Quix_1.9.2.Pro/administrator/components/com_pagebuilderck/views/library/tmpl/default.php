<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

$filePath = JPATH_ROOT . '/plugins/system/pagebuilderckparams/includes/library.php';
if (PagebuilderckHelper::getParams() && file_exists($filePath)) {
	include_once($filePath);
} else {
	echo 'ERROR : you can not access this page. Page Builder CK Params is not installed / enabled.';
}