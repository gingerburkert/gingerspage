<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$text = JText::_('COM_QUIX_TOOLBAR_ACTIVATION');
?>
<a
	rel="{handler:'iframe', size:{x:700,y:350}}"
	href="index.php?option=com_quix&amp;view=config&amp;tmpl=component"
	title="<?php echo $text; ?>" class="quixSettings btn btn-small"
	id="mySettings">
		<span class="icon-lock"></span> <?php echo $text; ?>
</a>
