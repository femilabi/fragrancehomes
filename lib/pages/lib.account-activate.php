<?php
// if(session_auth()){
// 	$APP->setSessionMsg('Your account is already activated!');
// 	redirect(HOME_DIR.'dashboard/');
// }

//Activate with hash

if ($APP->p(1)) {
	$code = new DBRow('account_activation_hash', 'id');
	$code->search('hash', $APP->p(1));
	if ($code->exists() && !$code->get('activated')) {
		$USER = new User($code->get('user_id'));
		if ($USER->exists() && !$USER->get('active')) {
			if ($USER->activate()) {
				$code->set('activated', 1);
				$code->save();
				$USER->set('final_activation_method', 'email');
				$USER->save();
				$APP->assign('user_firstname', $USER->get('firstname'));
				$APP->assign('user_email', $USER->get('email'));
				$APP->assign('user_phone', $USER->get('phone'));
				$APP->setTitle('Congratulations');
				$APP->setPage('activated');
				$replacements = $USER->get_data();
				//@send_email_template(array($USER->get('email')=> $USER->get('fullname')), 'activated', $replacements); //send email with the hash\
			} else {
				$APP->setMsg('Error activating your account! Please contact our support team', 'error');
				$APP->setPage('login/');
			}
		} else {
			$APP->setMsg('Invalid activation link, please check your email again', 'error');
			$APP->setPage('login/');
		}
	} else {
		$APP->setMsg('Invalid activation link, please check your email again', 'error');
		$APP->setPage('login/');
	}
	return;
}

$username = @$_GET['username'] ? $_GET['username'] : '';
$step = @$_GET['step'] ? $_GET['step'] : '';
if (!$username) {
	$APP->setSessionMsg('Username for activation not specified!', 'warning');
	redirect(HOME_DIR . 'login/');
}

$user = new User();
$user->load($username);
if ($user->exists() && !$user->get('active')) {
	if (!($step == 'activation' && $user->get('activation_sent'))) $step = 'activation_method';
	$APP->assign('user_fullname', $user->get('fullname'));
	$APP->assign('user_email', $user->get('email'));
	$APP->assign('user_phone', $user->get('phone'));
	$APP->setPage($step);
	$APP->setTitle('Account activation');

	if ($step == 'activation') {
		$APP->assign('user_activation_method', $user->get('activation_method'));
		if (issubmit() && $user->get('activation_method') == 'phone' && isset($_POST['form']['code'])) {
			$code = new DBRow('account_activation_hash', 'id');
			$code->search('user_id', $user->get('id'));
			if ($code->exists() && !$code->get('activated')) {
				if ($code->get('sms_code') == $_POST['form']['code']) {
					if ($user->activate()) {
						$code->set('activated', 1);
						$user->set('units', $APP->getSettings('ng_new_account_bonus'));
						$user->set('final_activation_method', 'phone');
						$code->save();
						$user->save();
						$APP->assign('user_firstname', $user->get('firstname'));
						$APP->assign('user_email', $user->get('email'));
						$APP->assign('user_phone', $user->get('phone'));
						$APP->setTitle('Activation Successful');
						$APP->setPage('activated');
						$replacements = $user->get_data();
						// @send_email_template(array($user->get('email')=> $user->get('fullname')), 'activated', $replacements); //send email with the hash\
						//send_sms_template($user->get('phone'), 'activation', $replacements, $user->get('country_id'), $user->get('country_code'));
					} else {
						$APP->setMsg('Error activating your account! Please contact our support team', 'error');
						$APP->setPage('login/');
					}
				} else {
					$APP->setMsg('Invalid activation code! Try again.', 'error');
				}
			} else {
				$APP->setSessionMsg('Error activating your account with this code. Please start activation proccess again', 'error');
				redirect(HOME_DIR . 'account-activate/?username=' . $user->get('username') . '&step=activation_method');
			}
		}
		return;
	}

	if (issubmit() || @$_GET['send_email']) {
		if (isset($_POST['activation_method']) || @$_GET['send_email']) {
			$method = $_POST['activation_method'] == 'phone' ? 'phone' : 'email';
			//override phone
			$method = 'email';
			$code = new DBRow('account_activation_hash', 'id');
			$code->search('user_id', $user->get('id'));
			if ($code->exists()) {
				$hash = array('hash' => $code->get('hash'), 'sms_code' => $code->get('sms_code'));
			} else {
				$hash = save_new_activation_hash_code($user->get('id'));
			}
			$replacements = $user->get_data();
			$replacements['activation_link'] = HOME_DIR . 'account-activate/' . $hash['hash'];
			$replacements['sms_code'] = $hash['sms_code'];
			$user->set('activation_sent', 1);

			$user->set('activation_method', $method);
			$user->save();
			//Send activation
			if ($method == 'phone') {
				//send_sms_template($user->get('phone'), 'activation', $replacements, $user->get('country_id'), $user->get('country_code'));
			} else {
				@send_email_template(array($user->get('email') => $user->get('fullname')), 'activation', $replacements);
			}
			redirect(HOME_DIR . 'account-activate/?username=' . $user->get('username') . '&step=activation');
		}
	}
} else {
	$APP->setSessionMsg('Invalid activation link, please try again!', 'error');
	redirect(HOME_DIR . '404/');
}
