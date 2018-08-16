<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// H4
Css::margin("#$id h4", $field['header_margin']);
Css::typography("#$id h4", $field['header_font']);
// Position
Css::typography("#$id .qx-person-position", $field['position_font']);
// Description
Css::typography("#$id .qx-person-description", $field['body_font']);
Css::margin("#$id .qx-person-description", $field['body_margin']);
// Person Image
Css::margin("#$id .qx-person-img", $field['image_margin']);
?>
#<?php echo $id?>{
  <?php Css::prop("background-color", $field['bg_color'])?>
  <?php if($field['bg_image']):?>
    background-image: url(<?php Css::image($field['bg_image']); ?>);
  <?php endif;?>
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
    box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
  <?php endif;?>
  <?php if($field['border']):?>
    <?php Css::prop("border-width", $field['border_width'] . 'px')?>
    <?php Css::prop("border-radius", $field['border_radius'] . 'px')?>
    <?php Css::prop("border-style", $field['border_style'])?>
    <?php Css::prop("border-color", $field['border_color'])?>
  <?php endif;?>
}
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

#<?php echo $id?> h4{
  <?php Css::prop("color", $field['header_color'])?>
}
#<?php echo $id?> .qx-person-position {
  <?php Css::prop("color", $field['position_color'])?>
}
<?php if($field['icon_color']):?>
  #<?php echo $id?> .qx-icon{ color: <?php echo $field['icon_color']?>; }
<?php endif;?>
<?php if($field['icon_hover_color']):?>
  #<?php echo $id?> .qx-icon:hover:before{ color: <?php echo $field['icon_hover_color']?>; }
<?php endif;?>
#<?php echo $id?> .qx-person-description{
 <?php Css::prop("color", $field['body_text_color'])?>
}

<?php //Image alignment center
if ($field['alignment'] == 'center'): ?>
#<?php echo $id?> .qx-person-img img {
  margin-left: auto;
  margin-right: auto;
}
<?php endif;?>

<?php //Image alignment right
if ($field['alignment'] == 'right'): ?>
#<?php echo $id?> .qx-person-img img {
  margin-left: auto;
}
<?php endif;?>


