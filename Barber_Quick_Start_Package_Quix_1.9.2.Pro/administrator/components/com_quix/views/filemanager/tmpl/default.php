<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0 ));

$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/quix/css/style.css?'. $document->getMediaVersion());
$input = $app->input;

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.

JFactory::getDocument()->addScriptDeclaration('
  Joomla.submitbutton = function(task)
  {
    if (task == "article.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
    {
      Joomla.submitform(task, document.getElementById("item-form"));
    }
  };
');
?>

<form action="<?php echo JRoute::_('index.php?option=com_quix'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
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
          <div class="item-list">
            <fieldset class="adminform ">
              <iframe class="iframe-bordered" style="width: 100%;min-height: 500px;" src="<?php echo JUri::root(); ?>media/quix/filemanager/index.php?type=0&relative_url=1&source=admin"></iframe>

            </fieldset>
          </div>
        </div>
      </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
  </div>
</form>
