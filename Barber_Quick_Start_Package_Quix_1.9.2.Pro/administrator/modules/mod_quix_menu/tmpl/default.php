<?php
/**
 * @package		Quix
 * @author 		ThemeXpert http://www.themexpert.com
 * @copyright	Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

// No direct access.
defined('_JEXEC') or die;
?>
<ul id="menu" class="nav nav-quix<?php echo ($hideMainmenu ? ' disabled' : ''); ?>">
	<li class="dropdown<?php echo ($hideMainmenu ? ' disabled' : ''); ?>">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<?php echo JText::_('COM_QUIX');?>
			<span class="caret"></span>
		</a>
		
		<?php if (!$hideMainmenu) : ?>
		<ul class="dropdown-menu">
			<!-- 
			<li>
				<a href="index.php?option=com_quix">
					<?php echo JText::_('MOD_QUIX_MENU_DASHBOARD'); ?>
				</a>
			</li>
			-->
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_quix&view=pages'); ?>">
					<?php echo JText::_('MOD_QUIX_MENU_PAGES'); ?>
				</a>
			</li>

			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_quix&view=collections'); ?>">
					<?php echo JText::_('MOD_QUIX_MENU_LIBRARIES'); ?>
				</a>
			</li>
			
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_quix&view=integrations'); ?>">
					<?php echo JText::_('MOD_QUIX_MENU_INTEGRATIONS'); ?>
				</a>
			</li>
			
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_quix&view=elements'); ?>">
					<span><?php echo JText::_('MOD_QUIX_MENU_ELEMENTS_LIST') ?></span>
				</a>
			</li>

			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_quix&view=filemanager'); ?>">
					<span><?php echo JText::_('MOD_QUIX_MENU_FILEMANAGER_LIST') ?></span>
				</a>
			</li>

			<li>
				<a href="https://www.facebook.com/groups/QuixUserGroup" target="_blank">
					<span><?php echo JText::_('MOD_QUIX_MENU_FEED_BACK') ?></span>
				</a>
			</li>

		</ul>
		<?php endif; ?>
	</li>
</ul>