jQuery(document).ready(function(){
jQuery('#<?php echo $id?>').slick({<?php echo implode(',', $script)?>});
});