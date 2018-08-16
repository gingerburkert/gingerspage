<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

JFactory::getDocument()->addScriptDeclaration("
		Joomla.submitbutton = function(task)
		{
			if (task == 'config.cancel' || document.formvalidator.isValid(document.getElementById('config-form')))
			{
				Joomla.submitform(task, document.getElementById('config-form'));
			}
		};
");
?>
<form action="<?php echo JRoute::_('index.php?option=com_quix'); ?>" method="post" name="adminForm" id="message-form" class="form-validate form-horizontal">
	<fieldset>
		<div>
			<div class="modal-header">
				<h3><?php echo JText::_('COM_QUIX_MY_SETTINGS');?></h3>
			</div>
			<div class="modal-body">

				<button class="btn btn-primary" type="submit" onclick="Joomla.submitform('config.save', this.form);window.top.setTimeout('window.parent.jModalClose();location.reload();', 700);">
					<?php echo JText::_('JSAVE');?></button>
				<button class="btn" type="button" onclick="window.parent.jModalClose();">
					<?php echo JText::_('JCANCEL');?></button>
				<hr />
				<div class="alert">
					<?php echo JText::_('COM_QUIX_MY_SETTINGS_DESC'); ?>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('username'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('username'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('key'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('key'); ?>
					</div>
				</div>
				
			</div>
		</div>
		</fieldset>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>

</form>
