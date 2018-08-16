<?php
/**
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the breadcrumbs functions only once
JLoader::register('ModQuixHelper', __DIR__ . '/helper.php');

// Get the breadcrumbs
$html  = ModQuixHelper::renderShortCode($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_quix', $params->get('layout', 'default'));
