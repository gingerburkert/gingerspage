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
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
    box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
  <?php endif;?>
}
<?php
  ###################################
  # Responsive Fields declear here
  ###################################
  
  Css::padding("#$id", $field['padding']);
?>
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
    content:''; position: absolute; width: 100%; top: 0; bottom: 0; left: 0;
    background-color: <?php echo $field['bg_color']; ?>
  }
<?php endif;?>

<?php if (($field['enable_top_shape']) || ($field['enable_bottom_shape'])) : ?>
#<?php echo $id ?> {
  position: relative;
}
#<?php echo $id ?> .qx-shape {
  position: absolute;
  left: 0;
  width: 100%;
  overflow: hidden;
}
#<?php echo $id ?> .qx-shape svg {
  display: block;
  width: 100%;
  position: relative;
  left: 50%;
  -webkit-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  transform: translateX(-50%);  
}
<?php endif; ?>

<?php if ($field['enable_top_shape']) : ?>
#<?php echo $id ?> .qx-shape.qx-shape-top {
  top: -1px;
  <?php if ($field['top_shape_bring_front']) : ?>
    z-index: 9999;
  <?php endif; ?>
}
#<?php echo $id ?> .qx-shape.qx-shape-top svg {
  <?php if ($field['top_shape_flip']) : ?>
    transform: translateX(-50%) rotateY(180deg);
  <?php endif; ?>
  width: <?php echo $field['top_shape_width'] ?>%;
  height: <?php echo $field['top_shape_height'] ?>px;
}
#<?php echo $id ?> .qx-shape.qx-shape-top svg .qx-shape-fill {
  <?php Css::prop('fill', $field['top_shape_color']); ?>
}
<?php endif; ?>

<?php if ($field['enable_bottom_shape']) : ?>
#<?php echo $id ?> .qx-shape.qx-shape-bottom {
  bottom: -1px;
  <?php if ($field['bottom_shape_bring_front']) : ?>
    z-index: 9999;
  <?php endif; ?>
  -webkit-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  transform: rotate(180deg);
}
#<?php echo $id ?> .qx-shape.qx-shape-bottom svg {
  <?php if ($field['bottom_shape_flip']) : ?>
  transform: translateX(-50%) rotateY(180deg);
  <?php endif; ?>
  width: <?php echo $field['bottom_shape_width'] ?>%;
  height: <?php echo $field['bottom_shape_height'] ?>px;
}
#<?php echo $id ?> .qx-shape.qx-shape-bottom svg .qx-shape-fill {
  <?php Css::prop('fill', $field['bottom_shape_color']); ?>
}
<?php endif; ?>

<?php if ($field['enable_video_bg']) : ?>
  #<?php echo $id; ?> .qx-column {z-index: 1;position: relative;}
<?php endif; ?>

<?php echo $renderer->render( $node['children'] ); ?>
