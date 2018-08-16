<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Add script js
JHtml::_('script', 'administrator/components/com_quix/assets/script.js', array('version' => 'auto'));
// Load specific CSS
JHtml::_('stylesheet', 'administrator/components/com_quix/assets/style.css', array('version' => 'auto'));

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
?>

<form action="<?php echo JRoute::_('index.php?option=com_quix'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
  <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
      <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
  <?php else : ?>
    <div id="j-main-container">
  <?php endif;?>
    <?php echo QuixHelper::randerSysMessage(); ?>
    <?php echo QuixHelper::getPHPWarning(); ?>
    <?php echo QuixHelper::checkFileManager(); ?>
    <?php echo QuixHelper::getFreeWarning(); ?>
    <?php echo QuixHelper::getUpdateStatus(); ?>

    <div class="form-horizontal">
      <div class="row-fluid">
        <div class="span12">
          <!-- <h3>
            <?php //echo JText::_('COM_QUIX_CONFIG_SPACER_COMPONENT_SUPPORT_LABEL'); ?>
            </h3>
           --><div class="alert clearfix">
            <?php echo JText::_('COM_QUIX_CONFIG_COMPONENT_SUPPORT_BANNER_LABEL'); ?>
          </div>
          <ul class="qx-elements-list clearfix">
          <?php 
            $fieldSets = $this->form->getFieldsets();
              foreach ($fieldSets as $name => $fieldSet) :
                foreach ($this->form->getFieldset($name) as $field):
                  $name = str_replace("enable_", "", $field->fieldname);
                if($name == 'custom_context') continue;
          ?>
            <li class="qx-element-list__item">
              <div class="success-message hide"><?php echo JText::_('JSAVED') ?></div>
              <div class="switch">
                <label>
                  <img class="qx-element__icon" 
                    src="<?php echo JUri::root() ?>/media/quix/images/integrations/<?php echo $name;?>.png" alt="<?php echo $name;?>">
                  <h4 class="qx-element__title"><?php echo $this->form->getLabel($field->fieldname) ?></h4>
                  <input 
                  class="toggleIntegration"
                  name="<?php echo $field->name; ?>"
                  data-element-slug="<?php echo $name;?>" 
                  type="checkbox"  
                  <?php echo $field->value ? 'checked' : ''?> />
                  <span class="lever"></span>
                </label>
              </div>
            </li>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </ul>

          <div class="item-list">
            <?php
              $fieldSets = $this->form->getFieldsets();
              foreach ($fieldSets as $name => $fieldSet) :
                foreach ($this->form->getFieldset($name) as $field):
                  $name = str_replace("enable_", "", $field->fieldname);
                  if($name != 'custom_context') continue;
                ?>
                <?php echo $field->getControlGroup(); ?>
                <?php
                endforeach;
              endforeach;
              ?>
              <div class="control-group">
                <div class="control-label"></div>
                <div class="controls">
                  <button id="customIntegrationSave" class="btn btn-primary"><?php echo JText::_('JAPPLY'); ?></button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
