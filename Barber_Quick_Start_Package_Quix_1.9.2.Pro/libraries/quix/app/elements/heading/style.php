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
Css::typography("#$id .qx-title", $field['title_font']);
Css::margin("#$id .qx-title", $field['title_text_margin']);
// Title > span
Css::typography("#$id .qx-title span", $field['title_span_font']);
// Subtitle
Css::typography("#$id .qx-subtitle *", $field['paragraph_font']);
Css::margin("#$id .qx-subtitle *", $field['paragraph_text_margin']);
?>

#<?php echo $id ?> {
  <?php Css::prop('background-color', $field['bg_color']);?>
  <?php if($field['enable_border']) :?>
    <?php Css::prop('border-width', $field['border_width']);?>
    <?php Css::prop('border-color', $field['border_color']);?>
    border-style: solid;
  <?php endif;?>
}

#<?php echo $id ?> .qx-title {
  margin-top: 0px; margin-bottom: 25px;
  <?php Css::prop('color', $field['title_text_color']);?>
}

#<?php echo $id ?> .qx-title span{
  <?php Css::prop('color', $field['title_span_color']);?>
}

#<?php echo $id ?> .qx-subtitle * {
  <?php Css::prop('color', $field['paragraph_text_color']);?>
}

<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?>
