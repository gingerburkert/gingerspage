<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; 
$app = JFactory::getApplication();
$messages = $app->getMessageQueue();
foreach ($messages as $key => $message) {
?>
<div class="alert alert-<?php echo str_replace(array('message', 'error', 'notice'), array('success', 'danger', 'info'), $message['type']); ?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4 class="alert-heading"><?php echo ucfirst($message['type']); ?></h4>
	<div class="alert-message"><?php echo $message['message']; ?></div>
</div>
<?php
}
