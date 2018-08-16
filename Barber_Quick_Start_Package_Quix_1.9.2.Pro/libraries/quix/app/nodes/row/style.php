<?php
###################################
# Responsive Fields declear here
###################################
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
?>

#<?php echo $id?>{
  <?php if($field['bg_image']):?>
    background-image: url(<?php Css::image($field['bg_image']); ?>);
    <?php Css::prop('background-repeat', $field['image_repeat']);?>
    <?php Css::prop('background-position', $field['image_position']);?>
    <?php Css::prop('background-size', $field['image_size']);?>
  <?php endif;?>
  <?php Css::prop('background-color', $field['bg_color']);?>
  <?php if($field['bg_overlay']):?>
    position: relative;
  <?php endif;?>
  <?php if($field['border']):?>
    <?php Css::prop("border-width", $field['border_width'] .'px')?>
    <?php Css::prop("border-radius", $field['border_radius'] .'px')?>
    <?php Css::prop("border-style", $field['border_style'])?>
    <?php Css::prop("border-color", $field['border_color'])?>
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

<?php 
  // Bg overlay
  if($field['bg_overlay']):?>
  #<?php echo $id?>:before{
    content:''; position: absolute; width: 100%; top: 0; bottom: 0; left: 0; display: block;
    background-color: <?php echo $field['bg_color']; ?>
  }
<?php endif;?>

<?php echo $renderer->render( $node['children'] ); ?>