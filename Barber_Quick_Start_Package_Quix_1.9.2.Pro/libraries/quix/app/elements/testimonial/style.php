<?php
###################################
# Responsive Fields
###################################
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Title
Css::typography("#$id .title", $field['title_font']);
Css::margin("#$id .title", $field['title_margin']);
// Designation 
Css::typography("#$id .designation", $field['designation_font']);
// Content Font
Css::typography("#$id .qx-testimony", $field['font']);
Css::margin("#$id .qx-testimony", $field['content_margin']);
// Image
Css::margin("#$id .testi-image img", $field['image_margin']);
?>
#<?php echo $id?>{
  <?php Css::prop('background-color', $field['bg_color']);?>  
}

#<?php echo $id?> .qx-testimony {
  <?php Css::prop('color', $field['text_color']);?>  
}
#<?php echo $id?> .title {
  <?php Css::prop('color', $field['title_color']);?>  
}
#<?php echo $id?> .designation{
  <?php Css::prop('color', $field['designation_color']);?>  
}