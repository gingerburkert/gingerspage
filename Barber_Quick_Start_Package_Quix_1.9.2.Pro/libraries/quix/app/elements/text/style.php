<?php
###################################
# Responsive Fields
###################################
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Fonts
Css::typography("#$id, #$id *", $field['font']);
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