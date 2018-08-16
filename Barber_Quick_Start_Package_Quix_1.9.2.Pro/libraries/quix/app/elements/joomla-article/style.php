<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Body Font
Css::typography("#$id", $field['body_font']);
// Title Font
Css::typography("#$id .qx-title", $field['title_font']);
// Image alignment
Css::float("#$id .item-image", $field['image_alignment']);
?>
#<?php echo $id?>{
  <?php Css::prop('background-color', $field['bg_color']);?>
  <?php Css::prop("color", $field['body_font_color'])?>
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
    box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
  <?php endif;?>
}

#<?php echo $id?> .qx-title{
	<?php Css::prop("color", $field['title_color'])?>
}
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 