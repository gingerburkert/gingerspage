<?php

/**
 * @version    1.0.0
 * @package    Contact Form Quix element
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
if( !defined('QX_ELEMENT_CONTACT_FORM') ):
JHtml::_('script', 'system/core.js', false, true);

?>
jQuery(function($){
	$('.qx-element-contact-form form').on('submit',function(event){
		event.preventDefault();
		var $self=$(this);
		var value=$(this).serializeArray();
		$.ajax({
			type:'post',
			data:value,
			beforeSend:function(){
				$self.find('.qx-btn').attr('disabled','disabled');
				$self.find('input, textarea').attr('disabled','disabled');
				$self.find('#qx-element-contact-form-msg').html('');
			},
			success:function(response){

				// Render the message
				if($.parseJSON(response).success)
				{
					$self.find("input[type=text], input[type=email], textarea").val("");
					$self.find('input, textarea').removeAttr('disabled');
					$self.find('#qx-element-contact-form-msg').html($.parseJSON(response).data).fadeIn();
				} 
				else 
				{
					$self.find('#qx-element-contact-form-msg').html($.parseJSON(response).message).fadeIn();
					$self.find('.qx-btn').removeAttr('disabled');
					$self.find('input, textarea').removeAttr('disabled');
				}
			},
			error:function(jqXHR, response) 
			{
				$self.find('#qx-element-contact-form-msg').html(response);
				$self.find('.qx-btn').removeAttr('disabled');
				$self.find('input, textarea').removeAttr('disabled');
			}
		});
	});
});
<?php
define('QX_ELEMENT_CONTACT_FORM', true);
endif;
?>