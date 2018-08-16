<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id .qx-btn", $field['padding']);
Css::typography("#$id .qx-btn", $field['font']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Icon
Css::margin("#$id i", $field['icon_margin']);
?>
#<?php echo $id?> .qx-btn {
  <?php Css::prop('color', $field['text_color']);?>
  <?php Css::prop('background', $field['bg_color']);?>
  <?php if($field['border']):?>
    <?php Css::prop('border-style', $field['border_style']);?>
    <?php Css::prop('border-color', $field['border_color']);?>
    <?php Css::prop('border-width', $field['border_width'] . 'px');?>
    <?php Css::prop('border-radius', $field['border_radius'] . 'px');?>
  <?php endif;?>
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
    box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
  <?php endif;?>
}

<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

<?php if($field['button_icon_color']):?>
  #<?php echo $id?> i{
    <?php Css::prop('color', $field['button_icon_color']);?>
  }
<?php endif;?>
  
<?php if( $field['text_color_hover'] OR 
          $field['bg_color_hover'] OR 
          $field['border_hover_color'] ):?>
  #<?php echo $id?> .qx-btn:hover{
    <?php Css::prop('color', $field['text_color_hover']);?>
    <?php Css::prop('background', $field['bg_color_hover']);?>
    <?php Css::prop('border-color', $field['border_hover_color']);?>
  }
<?php endif;?>

<?php if( $field['border'] ) :?>
   #<?php echo $id?>:before{
    <?php Css::prop('border-color', $field['border_hover_color']);?>
  }       
<?php endif;?>

<?php if($field['button_icon_color_hover']):?>
  #<?php echo $id?>:hover i{
    <?php Css::prop('color', $field['button_icon_color_hover']);?>
  }
<?php endif;?>