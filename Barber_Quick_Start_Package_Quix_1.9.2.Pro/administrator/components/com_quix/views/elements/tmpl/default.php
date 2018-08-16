<?php
/**
 * @version    1.1.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
?>
<?php if (!empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
  <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
  <?php else : ?>
  <div id="j-main-container">
    <?php endif; ?>
    <?php echo QuixHelper::randerSysMessage(); ?>
    <?php echo QuixHelper::getPHPWarning(); ?>
    <?php echo QuixHelper::checkFileManager(); ?>
    <?php echo QuixHelper::getFreeWarning(); ?>
    <?php echo QuixHelper::getUpdateStatus(); ?>

    <div class="clearfix"></div>

    <ul class="qx-elements-list clearfix">
      <?php $elements = quix()->getElements(); ?>
      <?php foreach ($elements as $element): ?>
        <li class="qx-element-list__item">
          <div class="switch">
            <label>
              <img class="qx-element__icon" src="<?php echo $element['thumb_file'] ?>" alt="Accordion">
              <h4 class="qx-element__title"><?php echo $element['name'] ?></h4>
              <input data-element-slug="<?php echo $element['slug'] ?>" type="checkbox"  <?php echo $element['enabled'] ? 'checked' : ''?>>
              <span class="lever"></span>
            </label>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
    
    <?php echo QuixHelper::getProElementBanner(); ?>

    <?php echo quix_footer_credit(QuixHelper::isFreeQuix()); ?>
      
    <?php echo JHtml::_('form.token'); ?>
    
  </div>

  <script>
    jQuery(function ($) {
      var token = '<?php echo JSession::getFormToken(); ?>';
      $('[data-element-slug]').on('change', function () {
        $.ajax({
          url: 'index.php?option=com_quix&task=updateElement',
          type: 'post',
          data: { status: this.checked ? 1:0, alias: this.dataset.elementSlug, token: token },
          success: function (response) {
            console.log(response);
          },
          error: function(error) {
            console.log(error);
          }
        });
      });
    });
  </script>
