<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Typography
Css::typography("#$id, #$id *", $field['font']);
// First letter
Css::typography("#{$id}:first-letter", $field['dcap_font']);
Css::margin("#{$id}:first-letter", $field['dcap_margin']);
Css::padding("#{$id}:first-letter", $field['dcap_padding']);
?>

#<?php echo $id?>{
  <?php Css::prop('color', $field['text_color']);?>
  <?php if($field['bg_image']):?>
    background-image : url(<?php Css::image($field['bg_image']); ?>);
    <?php Css::prop('background-repeat', $field['image_repeat']);?>
    <?php Css::prop('background-position', $field['image_position']);?>
    <?php Css::prop('background-size', $field['image_size']);?>
  <?php endif;?>
  <?php Css::prop('background-color', $field['bg_color']);?>
}

#<?php echo $id?> *{
  <?php Css::prop('color', $field['text_color']);?>
}

#<?php echo $id?>:first-letter {
float: left;
color: #903; font-size: 75px; line-height: 60px;
padding-top: 4px; padding-right: 8px; padding-left: 3px;
<?php Css::prop('color', $field['dcap_text_color']);?>
<?php Css::prop('background', $field['dcap_bg_color']);?>

<?php if($field['border']):?>
  <?php Css::prop('border-style', $field['border_style']);?>
  <?php Css::prop('border-color', $field['border_color']);?>
  <?php Css::prop('border-width', $field['border_width'] . 'px');?>
  <?php Css::prop('border-radius', $field['border_radius'] . 'px');?>
<?php endif;?>
}