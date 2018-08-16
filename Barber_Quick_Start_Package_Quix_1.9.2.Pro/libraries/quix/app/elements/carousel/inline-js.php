jQuery(document).ready(function(){
    var mySwiper = new Swiper ('#<?php echo $id ?>', {
    <?php echo implode(',', $script)?>              
    });
});