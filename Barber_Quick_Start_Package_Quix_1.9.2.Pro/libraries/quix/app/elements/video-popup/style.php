<?php
###################################
# Responsive Fields
###################################
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Link
Css::typography("#$id a, #$id.qx-btn", $field['text_font']);
if($field['button_style']){  
  // Button
  Css::padding("#{$id}.qx-btn", $field['button_padding']);
}
?>

#<?php echo $id?> a {
<?php Css::prop("color", $field['text_color'])?>
}

<?php if($field['button_style']):?>
#<?php echo $id;?>.qx-btn{
  <?php Css::prop('background-color', $field['button_bg']);?>
  <?php Css::prop('color', $field['button_text']);?>
  <?php Css::prop('border-color', $field['button_border_color']);?>
  <?php Css::prop('border-width', $field['button_border_width'] . 'px');?>
  <?php Css::prop('border-radius', $field['button_border_radius'] . 'px');?>
}

#<?php echo $id;?>.qx-btn:hover{
<?php Css::prop('background-color', $field['button_bg_hover']);?>
<?php Css::prop('color', $field['button_text_hover']);?>
<?php Css::prop('border-color', $field['button_border_color_hover']);?>
}
<?php endif;?>

