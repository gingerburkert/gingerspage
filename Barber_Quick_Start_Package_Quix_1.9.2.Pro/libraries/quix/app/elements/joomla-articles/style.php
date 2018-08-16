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
Css::typography("#$id .qx-media-heading, #$id .qx-media-heading a", $field['title_font']);
Css::margin("#$id .qx-media-heading, #$id .qx-media-heading a", $field['title_margin']);
// Introtext
if( $field['show_introtext'] ){  
  Css::typography("#$id .qx-element-jarticle-introtext", $field['introtext_font']);
  Css::margin("#$id .qx-element-jarticle-introtext, #$id .qx-media-heading a", $field['introtext_margin']);
}
// Readmore
if( $field['show_readmore'] ){
  Css::typography("#$id .qx-btn", $field['readmore_font']);
}
// Media
Css::margin("#$id .qx-media-grid, #$id .qx-media", $field['bg_margin']);
Css::padding("#$id .qx-media-grid, #$id .qx-media", $field['bg_padding']);
?>

#<?php echo $id?> .qx-media-heading,
#<?php echo $id?> .qx-media-heading a{
  <?php Css::prop("color", $field['title_color'])?>
}
<?php if( $field['show_introtext'] ):?>
#<?php echo $id?> .qx-element-jarticle-introtext{
  <?php Css::prop("color", $field['introtext_color'])?>
}
<?php endif;?>

<?php if('list' === $field['layout']):?>
<?php Css::width("#$id .qx-media-object", $field['image_size']); ?>
<?php endif;?>

#<?php echo $id?> .qx-media-grid,
#<?php echo $id?> .qx-media{
	<?php Css::prop('background-color', $field['bg_color']);?>

	transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
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

@media (max-width: 600px) {
  #<?php echo $id ?> figure {
    display: block;
    margin-bottom: 15px;
    padding: 0;
  }
  #<?php echo $id ?> figure img.qx-media-object {
    width: 100%;
  }
}