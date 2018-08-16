<?php

/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';

$app = JFactory::getApplication();
$input = $app->input;

$id = $input->get('ckobjid', '', 'string');
$class = $input->get('objclass', '', 'string');
//$fields = stripslashes( $input->get('fields', '', 'string'));
$fields = $input->get('fields', '', 'string');
$fields = json_decode($fields);
$customstyles = stripslashes( $input->get('customstyles', '', 'string'));
$customstyles = json_decode($customstyles);
$action = $input->get('action', '', 'string');
$cssstyles = new CssStyles();
$styles = $cssstyles->create($fields, $id, $action, $class, 'ltr', $customstyles);

if ($action == 'preview') {
	echo '<style>' . $styles . '</style>';
} else {
	return $styles;
}

exit();