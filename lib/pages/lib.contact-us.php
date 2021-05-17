<?php $APP->setTitle('Contact Us');
if (issubmit()) {
	//Validate reCaptcha
	$recaptcha_response = @$_POST['g-recaptcha-response'];
	if (!$recaptcha_response) {
		die('Access denied');
	}
	$recaptcha_params = array(
		'secret' => RECAPTCHA_PRIV_KEY,
		'response' => $recaptcha_response,
		'remoteip' => $_SERVER['REMOTE_ADDR']
	);
	$response = @json_decode(send_remote_request('https://www.google.com/recaptcha/api/siteverify', '', $recaptcha_params), true);

	if (!(is_array($response) && @$response['success'])) {
		die('Access denied');
	}

	$contact = isset($_POST['contact']) ? $_POST['contact'] : '';
	$message = 'Contact form was submited: on Geo-Network';
	$message .= '<br/>Name: ' . (isset($contact['name']) ? $contact['name'] : '');
	$message .= '<br/>Email: ' . (isset($contact['email']) ? $contact['email'] : '');
	$message .= '<br/>Message: ' . (isset($contact['message']) ? $contact['message'] : '');
	$message .= '<br/>Time submitted: ' . date('d-m-Y, H:i:s', time());
	@notify_admins('[contact] ' . @$contact['subject'], $message, $contact['email']);
	$APP->setMsg('Your message has been sent successfully<br/>You should hear from us shortly<br/>Thank you for contacting us!');
}
