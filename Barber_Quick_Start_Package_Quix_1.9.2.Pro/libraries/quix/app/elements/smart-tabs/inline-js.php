jQuery(function() {

  jQuery('#<?php echo $id ?> .qx-triggers').each(function(key, val){
    var target = jQuery(this).data('target');

    jQuery(target).addClass('qx-st-content animated <?php echo $content_animation?>');

    if( key == 0 ){
        jQuery(this).addClass('qx-active');
        jQuery(target).addClass('qx-active');
    }
  })

  jQuery('#<?php echo $id ?> .qx-triggers').on('click', function(){
    var target = jQuery(this).data('target');

    jQuery('#<?php echo $id ?> .qx-triggers').removeClass('qx-active')

    jQuery(this).addClass('qx-active')

    jQuery('#<?php echo $id ?> .qx-triggers').each(function(){
      jQuery( jQuery(this).data('target') ).removeClass('qx-active');
    })

    jQuery( target ).addClass('qx-active');

  })

});
