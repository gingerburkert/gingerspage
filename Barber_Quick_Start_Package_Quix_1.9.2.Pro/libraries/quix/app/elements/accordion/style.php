<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Collapsible header
Css::margin("#$id .collapsible-header", $field['title_margin']);
Css::padding("#$id .collapsible-header", $field['title_padding']);
Css::typography("#$id .collapsible-header", $field['header_font']);
// Icon font size
Css::fontSize("#$id .collapsible-header i", $field['icon_font_size']);
// Collapsible body
Css::margin("#$id .collapsible-body", $field['body_margin']);
Css::padding("#$id .collapsible-body", $field['body_padding']);
Css::typography("#$id .collapsible-body", $field['body_font']);
?>

<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

#<?php echo $id ?> .collapsible-header{
  <?php Css::prop("background-color", $field['header_bg_color'])?>
  <?php Css::prop("color", $field['header_text_color'])?>
}

#<?php echo $id ?> .collapsible-header i {
<?php Css::prop("color", $field['icon_color'])?>
}

#<?php echo $id ?> .collapsible-body{
  <?php Css::prop("background-color", $field['body_bg_color'])?>
  <?php Css::prop("color", $field['body_text_color'])?>
}

#<?php echo $id?> .collapsible-body,
#<?php echo $id?> .collapsible li{
  <?php Css::prop("border-color", $field['border_color'])?>
}


