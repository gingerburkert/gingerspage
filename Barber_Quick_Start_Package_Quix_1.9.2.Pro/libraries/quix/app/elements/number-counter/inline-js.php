jQuery(document).ready(function(){
  var appeared = false;
  jQuery("#<?php echo $id?>").appear();
  jQuery("#<?php echo $id?>").on("appear", function(){
    if(appeared) return;
    appeared = true;
    var range = jQuery(this).attr('data-range');
    jQuery(this).find('.qx-nc-number').countTo({
      from: 0,
      to: range,
      speed: 2000,
      refreshInterval: 50,
    });
  });
});
