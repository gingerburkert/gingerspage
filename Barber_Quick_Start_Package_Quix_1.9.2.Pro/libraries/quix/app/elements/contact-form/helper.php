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


/**
* QuixSimpleContactElement helper class
*/
class QuixContactFormElement
{
	
	public static function getAjax()
	{
		// Check for request forgeries.
		if( !JSession::checkToken('post') ){
			return new Exception("<div class='alert alert-danger'>" . JText::_('JINVALID_TOKEN') . "</div>");
		}


		$lang = JFactory::getLanguage();
		$extension = 'contact-form';
		$base_dir = dirname(__FILE__);
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);


		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$data   = $app->input->get('jform', array(), 'array');
		$info = json_decode(base64_decode($data['info']));

        $recipient = $info->email;
        if( empty($recipient))
        {
        	$recipient = $config->get('mailfrom');
        }

		$form = JForm::getInstance('ContactForm', dirname(__FILE__) . '/form/form.xml',array('control' => 'jform'));
		
		if( $info->captcha_enabled )
		{
			$form->loadFile(dirname(__FILE__).'/form/form_captcha.xml', false);		
		}

		$result = $form->validate($data);

		// Check for validation errors.
		if ($result === false)
		{
			// codes goes here
			$return = '';
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message)
			{
				$return .= $message->getMessage();
			}

			//now you can return error msg
			return $return;
		}

		/*
		* validation successful, do your job here
		*/
		
		// Get the input value from data array
        $name = $data['name'];
        $email = JStringPunycode::emailToPunycode($data['email']);
        $subject = $data['subject'];
        $body = $data['body'];

        // Now send email
        $mail = JFactory::getMailer();
        $mail->addRecipient($recipient);
        $mail->addReplyTo($email, $name);
        $mail->setSender(array($email, $name));
        $mail->setSubject($subject);
        $mail->setBody($body);
        $mail->isHTML(true);
		$mail->Encoding = 'base64';	

		if ( $mail->Send() !== true ) 
        {
			// get the error
			return new Exception(JText::sprintf('COM_QUIX_EMAIL_SENT_ERROR', $mail->ErrorInfo));
        }
        else
        {
        	// return success
			return JText::_('COM_QUIX_EMAIL_SENT_SUCCESSFUL');
        }

	}

}

 ?>