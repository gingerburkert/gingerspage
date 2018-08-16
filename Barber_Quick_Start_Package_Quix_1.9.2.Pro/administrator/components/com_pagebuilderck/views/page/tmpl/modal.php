<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$input = new JInput();
$user		= JFactory::getUser();
$app		= JFactory::getApplication();

$assoc		= isset($app->item_associations) ? $app->item_associations : 0;
$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
?>

<?php 
// loads the css and js files
echo $this->loadTemplate('include'); 
?>

<button id="applyBtn" type="button" class="hidden" onclick="Joomla.submitbutton('page.apply');"></button>
<button id="saveBtn" type="button" class="hidden" onclick="Joomla.submitbutton('page.save');"></button>
<button id="closeBtn" type="button" class="hidden" onclick="Joomla.submitbutton('page.cancel');"></button>
<?php 
// loads the main content
echo $this->loadTemplate('main');
