jQuery(document).ready(function(){
	var appeared = false;
	jQuery("#<?php echo $id?>").appear();
	jQuery("#<?php echo $id?>").on("appear", function(){
		if(appeared) return;
		appeared = true;
		jQuery(this).find('.qx-progress').each(function(){
			var progress = jQuery(this).data('progress') + '%';
			jQuery(this).find('.qx-progress-bar').css('width', progress);
		});
	});
});