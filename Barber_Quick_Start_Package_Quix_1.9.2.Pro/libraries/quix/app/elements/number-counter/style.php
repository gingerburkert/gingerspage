<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Title
Css::typography("#$id .qx-nc-title", $field['title_font']);
Css::typography("#$id .qx-nc-number", $field['number_font']);
// Icon font
Css::fontSize("#$id .qx-icon", $field['icon_font_size']);
?>
#<?php echo $id?>{
  <?php if($field['bg_image']):?>
    background-image : url(<?php Css::image($field['bg_image']); ?>);
    <?php Css::prop('background-repeat', $field['image_repeat']);?>
    <?php Css::prop('background-position', $field['image_position']);?>
    <?php Css::prop('background-size', $field['image_size']);?>
  <?php endif;?>
  <?php Css::prop('background-color', $field['bg_color']);?>
}
#<?php echo $id?> .qx-nc-title{
  <?php Css::prop("color", $field['title_color'])?>
}
#<?php echo $id?> .qx-nc-number{
  <?php Css::prop("color", $field['number_color'])?>
}
<?php if($field['icon_enabled']):?>
#<?php echo $id?> .qx-icon{
  <?php Css::prop("color", $field['icon_color'])?>
}
<?php endif;?>