<?php
###################################
# Responsive Fields
###################################
#
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Title
Css::typography("#$id .qx-title", $field['title_font']);
Css::margin("#$id .qx-title", $field['title_margin']);
// Description
Css::typography("#$id .qx-description", $field['description_font']);
// Button
Css::typography("#$id .qx-btn", $field['button_font']);
Css::margin("#$id .qx-ctab", $field['btn_margin']);

//Style 2
Css::alignment("#$id .style_2", $field['alignment']);
?>
#<?php echo $id; ?> {
  <?php Css::prop('background-color', $field['bg_color']);?>
}
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

#<?php echo $id; ?> .qx-title { 
  <?php Css::prop('color', $field['title_color']);?>
}

#<?php echo $id; ?> .qx-description {
  <?php Css::prop('color', $field['description_color']);?>
}