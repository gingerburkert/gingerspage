<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Blurb Title
Css::typography("#$id .qx-blurb-title", $field['header_font']);
Css::margin("#$id .qx-blurb-title", $field['header_margin']);
// Blurb Content
Css::typography("#$id .qx-blurb-content", $field['body_font']);
// Media
Css::margin("#$id .qx-icon, #$id .qx-image", $field['media_margin']);
// Icon
Css::fontSize("#$id .qx-icon", $field['icon_font_size']);
?>

<?php if($field['link_type'] == 'full_section') : ?>
  #<?php echo $id?> a { display: block; }
<?php endif; ?>

#<?php echo $id?>{
  <?php Css::prop("background-color", $field['bg_color'])?>
  <?php if($field['bg_image']):?>
    background: url(<?php Css::image($field['bg_image']); ?>) no-repeat;
    <?php Css::prop('background-repeat', $field['image_repeat']);?>
    <?php Css::prop('background-position', $field['image_position']);?>
    <?php Css::prop('background-size', $field['image_size']);?>
  <?php endif;?>
   <?php if($field['border']):?>
    border-width: <?php echo $field['border_width']?>px;
    border-radius: <?php echo $field['border_radius']?>px;
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

#<?php echo $id?> .qx-blurb-content {
  <?php Css::prop("color", $field['body_color'])?>
}
#<?php echo $id?> .qx-blurb-title{
  <?php Css::prop("color", $field['header_color'])?>
}
<?php if($field['header_hover_color']):?>
  #<?php echo $id?> .qx-blurb-title:hover{
    <?php Css::prop("color", $field['header_hover_color'])?>
  }
<?php endif;?>

#<?php echo $id?> .qx-icon{
  <?php Css::prop("color", $field['icon_color'])?>
}
<?php if( $field['bg_hover_color'] OR $field['border_hover_color'] ):?>
  #<?php echo $id?>:hover{
    <?php Css::prop('background', $field['bg_hover_color']);?>
    <?php Css::prop('border-color', $field['border_hover_color']);?>
  }
<?php endif;?>