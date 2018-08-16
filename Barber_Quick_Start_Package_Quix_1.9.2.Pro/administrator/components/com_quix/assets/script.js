(function($){

  	$(document).ready(function(){
  		function saveAjaxIntegration(item){
  			$('input[name=task]').val('integrations.update');
		    // console.log($(this).is(':checked'));
		    
			var value   = $('#adminForm').serializeArray();
			// console.log(value);
			$.ajax({
				type   : 'POST',
				data   : value,
				beforeSend: function(){
		          	item.parent().parent().parent().addClass('disabled');
				    item.attr('disabled', true);
		        },
				success: function (res) {
					var response = JSON.parse(res);
					if(!response.success){
						console.log(response.data);
					}
					item.parent().parent().parent().removeClass('disabled');
				    item.attr('disabled', false);
					item.parent().parent().parent().find('.success-message').fadeIn('fast').delay(1000).fadeOut('fast');
				}
			});      
  		}

		$('.toggleIntegration').change(function() {
			var item = $(this);
			saveAjaxIntegration(item);
	 	});

	 	$('#customIntegrationSave').on('click', function(e) {
	 		e.preventDefault();
			var item = $(this);
			saveAjaxIntegration(item);
	 	});
 	});

})(jQuery);