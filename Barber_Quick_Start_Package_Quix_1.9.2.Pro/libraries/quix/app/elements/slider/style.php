<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id .qx-slide", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
Css::typography("#$id", $field['body_font']);
// Title
Css::typography("#$id .qx-slide h3", $field['header_font']);
?>
#<?php echo $id;?>{
  <?php Css::prop('color', $field['body_color']);?>
}
#<?php echo $id; ?> .qx-slide h3 {
  <?php Css::prop('color', $field['header_color']); ?>
}
#<?php echo $id;?> .slick-prev:before, 
#<?php echo $id;?> .slick-next:before{
 <?php Css::prop('color', $field['body_color']);?> 
}