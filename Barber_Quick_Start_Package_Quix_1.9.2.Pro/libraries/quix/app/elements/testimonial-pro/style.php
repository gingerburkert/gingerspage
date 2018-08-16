<?php
###################################
# Responsive Fields
###################################
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
Css::alignment("#$id", $field['alignment']);
// Testimony
Css::typography("#$id .qx-testimony", $field['font']);
// H4 - Name
Css::typography("#$id h4", $field['name_font']);
// Designation
Css::typography("#$id .qx-designation", $field['designation_font']);
Css::margin("#$id .qx-designation", $field['designation_margin']);
// Image 
Css::margin("#$id .qx-img-responsive", $field['image_margin']);
?>

#<?php echo $id?>{
  <?php Css::prop('background-color', $field['bg_color']);?>  
}

#<?php echo $id?> .qx-testimony{
  <?php Css::prop('color', $field['text_color']);?>  
}
#<?php echo $id?> h4{
  <?php Css::prop('color', $field['name_color']);?>  
}
#<?php echo $id?> .qx-designation{
  <?php Css::prop('color', $field['designation_color']);?>  
}
#<?php echo $id?> .slick-prev:before, 
#<?php echo $id?> .slick-next:before{
  <?php Css::prop('color', $field['nav_color']);?>
}
#<?php echo $id?> .slick-dots li button:before {
    <?php Css::prop('color', $field['dots_nav_color']);?>
}
#<?php echo $id?> .slick-dots li.slick-active button:before {
    <?php Css::prop('color', $field['dots_nav_color']);?>
}