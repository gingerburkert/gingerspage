<?php
###################################
# Responsive Fields
###################################
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Font
Css::typography("#$id .tab-content", $field['fonts']);
Css::typography("#$id .tab a", $field['title_fonts']);
?>

#<?php echo $id;?> .tab a{
<?php Css::prop("color", $field['nav_text'])?>
}
#<?php echo $id;?> .tab:hover a,
#<?php echo $id;?> .tab a.active{
  <?php Css::prop("background", $field['nav_active_bg'])?>
  <?php Css::prop("color", $field['nav_active_text'])?>
  <?php Css::prop("border-color", $field['nav_border_color'])?>
}
#<?php echo $id;?> .tab-content{
  <?php Css::prop("background", $field['body_bg_color'])?>
  <?php Css::prop("color", $field['body_text_color'])?>
}
