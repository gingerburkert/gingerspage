<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; 
?>
<div class="alert alert-danger clearfix">
  <!-- <a href="#" class="btn pull-right right" target="_blank">Update Guide</a> -->
  <h3 class="text-uppercase"><?php echo JText::_('COM_QUIX_HEADS_UP_OLD_PHP'); ?></h3>
  <p><?php echo JText::sprintf('COM_QUIX_PHP_WARNING_OLD_VERSIONS', phpversion()); ?></p>
</div>
