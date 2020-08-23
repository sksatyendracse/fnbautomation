<?php
require_once(__DIR__ . '/inc/config.php');
require_once($lang_folder . '/_trans-contact.php');

// check csrf token
require_once(__DIR__ . '/user/_user_inc_request_with_ajax.php');

// v.1.13
if(!isset($txt_message_sent)) $txt_message_sent = "Message sent";

// initialize swiftmailer
$transport_smtp = (new Swift_SmtpTransport($smtp_server, $smtp_port, $cfg_smtp_encryption))
	->setUsername($smtp_user)
	->setPassword($smtp_pass);

$mailer = new Swift_Mailer($transport_smtp);

// get post data
$params = array();
parse_str($_POST['params'], $params);

// posted vars
$contact_name    = !empty($params['name'   ]) ? $params['name'   ] : '';
$contact_email   = !empty($params['email'  ]) ? $params['email'  ] : '';
$contact_subject = !empty($params['subject']) ? $params['subject'] : '';
$contact_message = !empty($params['message']) ? $params['message'] : '';

// sender ip
$sender_ip = get_ip();

// send message
if(filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
	$message = (new Swift_Message())
		->setSubject($contact_subject)
		->setFrom(array($contact_email => $contact_name))
		->setTo($admin_email)
		->setBody($contact_message)
		->setReplyTo($admin_email)
		->setReturnPath($contact_email)
		;

	// Send the message
	if($mailer->send($message)) {
		echo $txt_message_sent;
	}

	else {
		echo 'error sending message';
	}
}

else {
	echo 'Invalid email address. Reload page and try again.';
}
