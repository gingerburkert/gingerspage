jQuery(document).ready(function(){
  var appeared = false;
  jQuery("#<?php echo $id?>").appear();
  jQuery("#<?php echo $id?>").on("appear", function(){
    if(appeared) return;
    appeared = true;
    var chart = jQuery('#<?php echo $id?>');
    chart.easyPieChart({
      size: 0 !== chart.width() ? chart.width() : 10, // set the width to 10 if actual width is 0 to avoid js errors
      scaleColor:false,
      onStart: function() {
        jQuery(this.el).find('.qx-percent p').css({ 'visibility' : 'visible' });
      },
      onStep: function(from, to, percent) {
        jQuery(this.el).find('.qx-percent-value').text( Math.round( parseInt( percent ) ) );
      },
      onStop: function(from, to) {
        jQuery(this.el).find('.qx-percent-value').text( jQuery(this.el).data('percent') );
      }
    });
  });
});