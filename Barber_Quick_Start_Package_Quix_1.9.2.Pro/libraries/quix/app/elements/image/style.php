<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Image
Css::width("#$id img", $field['image_width']);
Css::height("#$id img", $field['image_height']);
?>
#<?php echo $id?> img{  
  <?php if($field['border']):?>
    <?php Css::prop('border-style', $field['border_style']);?>
    <?php Css::prop('border-color', $field['border_color']);?>
    border-width: <?php echo $field['border_width']?>px;
    border-radius: <?php echo $field['border_radius']?>px;
  <?php endif;?>
}

<?php if( $field['border_hover_color'] ):?>
  #<?php echo $id?> img:hover {
    <?php Css::prop('border-color', $field['border_hover_color']);?>
  }
<?php endif;?>

<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 
