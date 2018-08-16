jQuery(document).ready(function(){
  jQuery('#<?php echo $id?> .qx-sliders').slick({<?php echo implode(',', $script)?>});
});