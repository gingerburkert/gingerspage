<?php
Css::typography("#$id .qx-pc-title", $field['title_font']);
?>
#<?php echo $id?> .qx-pc-title{
  <?php Css::prop('color', $field['title_color']);?>
}
#<?php echo $id?> .qx-percent-value,
#<?php echo $id?> .qx-percent {
  <?php Css::prop('color', $field['percentage_color']);?>
}