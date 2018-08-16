jQuery(document).ready(function(){
	jQuery('#<?php echo $id?> .qx-image--gallery').magnificPopup({
    type:'image',
    removalDelay: 500,
    mainClass: 'mfp-fade',
    gallery:{
      enabled:true,
      navigateByImgClick: true,
    },
    zoom: {
      enabled: true,
      duration: 500,
      opener: function(element) {
        return element.find('img');
      }
    }
  });
});