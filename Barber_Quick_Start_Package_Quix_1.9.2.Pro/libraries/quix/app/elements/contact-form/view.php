<?php

/**
 * @version    1.0.0
 * @package    Contact Form Quix element
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$lang = JFactory::getLanguage();
$extension = 'contact-form';
$base_dir = dirname(__FILE__);
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

jimport( 'joomla.form.form' );
$form = JForm::getInstance('ContactForm', dirname(__FILE__) . '/form/form.xml',array('control' => 'jform'));
$loadcaptcha = (isset($field['captcha_enabled']) ?  $field['captcha_enabled'] : 0 );
if($loadcaptcha)
{
  $form->loadFile(dirname(__FILE__).'/form/form_captcha.xml', false);
}

$fieldSets = $form->getFieldsets();

$classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
  "wow {$field['animation']}" => $field['animation'],
  "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
] );
// Animation delay
$animation_delay = '';
if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
  $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
}

?>
<!-- qx-element-contact-form -->
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>

    <form class="form-horizontal" method="post">

        <fieldset>
        
            <?php
                foreach ($fieldSets as $name => $fieldSet) :
                    foreach ($form->getFieldset($name) as $fieldItem):
                        echo $fieldItem->getControlGroup();
                    endforeach;
                endforeach;
            ?>
            
            <div class="control-group">

                <div class="controls">
                    
                    <div id="qx-element-contact-form-msg"></div>

                    <button id="qx-element-contact-form-submit" type="submit" class="qx-btn qx-btn-primary">
                        
                        <?php echo JText::_('QX_CF_SEND');?>
                    
                    </button>

                </div>
            </div>
            
            <input type="hidden" name="option" value="com_quix" />
            <input type="hidden" name="task" value="ajax" />
            <input type="hidden" name="element" value="contact-form" />
            <input type="hidden" name="format" value="json" />
            <input type="hidden" name="jform[info]" 
                value="<?php echo base64_encode(json_encode($field)); ?>" />

            <?php echo JHtml::_('form.token'); ?>
        
        </fieldset>

    </form>

</div>
<?php 
Assets::js( 'qx-element-contact-' . $id, dirname(__FILE__) . '/js/inline-js.php' );
?>
<!-- qx-element-contact -->
