<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id i", $field['margin']);
Css::padding("#$id i", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Font
Css::fontSize("#$id i", $field['icon_size']);
?>
#<?php echo $id; ?> i{
  <?php Css::prop('color',$field['color']);?>
  <?php if($field['border']):?>
    border-style: solid;
    <?php Css::prop('border-width',$field['border_width'] . 'px');?>
    <?php Css::prop('border-radius',$field['border_radius'] . 'px');?>
    <?php Css::prop('border-color',$field['border_color']);?>
  <?php endif;?>
}
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 