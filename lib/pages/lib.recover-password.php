<?php
if(session_auth()){
	$APP->setSessionMsg('You are currently logged in!');
	redirect(HOME_DIR.'dashboard/');
}
$APP->setTitle('Account Recovery');
if(issubmit()){
	$data = @$_POST['form'];
	if(!empty($data['username']) && !empty($data['action']) && $data['action'] == 'generate'){
		$u = new User($data['username']);
		if($u->exists()){
			$replacements = $u->get_data();
			$APP->assign('user_data', $u->get_data(array('username','email','fullname')));
			$hash = save_new_password_hash($u->get('id'));
			$replacements['recovery_link'] = HOME_DIR.'recover-password/'.$hash.'/';
			@send_email_template(array($u->get('email')=> $u->get('fullname')), 'password_recovery', $replacements);
			$APP->setPage('recovery_email_sent');
		}
	}elseif(!empty($data['action']) && $data['action'] == 'change_password' && !empty($_GET['hash'])){
		$hash = $_GET['hash'];
		$code = new DBRow('password_hash','id');
		$code->search('hash',$hash);
		if($code->exists() && !$code->get('activated')){
			if(!validate_recaptcha()){
			  // What happens when the CAPTCHA was entered incorrectly
			  $APP->setMsg('Error! Captcha check not passed. Please try again.','error');
			  return;
			}
			$user = new User($code->get('user_id'));
			if($user->exists()){
				$APP->assign('user_data', $user->get_data(array('username','email','fullname')));
				$password = (@$data['password'] && @$data['password2'] && $data['password'] == $data['password2'])? $data['password'] : '';
				if(!$password){
				  $APP->setMsg('Password does not match!','error');
				  return;
				}
				$u = new DBRow('users','id', $user->get('id'));
				if($password && $u->exists()){
					if(strtolower($data['secure_answer']) != strtolower($u->get('secure_answer'))){
					  $APP->setMsg('Wrong answer to security check!','error');
					  return;
					}
					$u->set('password',User::hash_password($password));
					$u->save();
					$code->set('activated',1);
					$code->save();
					$APP->setSessionMsg('Your password was changed successfully!<br/>Login to your account to continue.');
					redirect(HOME_DIR.'empire/login/');
				}
			}
		}else $APP->setMsg('Invalid recovery link');
	}
}elseif(!empty($_GET['hash'])){
		$hash = $_GET['hash'];
		$code = new DBRow('password_hash','id');
		$code->search('hash',$hash);
		if($code->exists() && !$code->get('activated')){
			$user = new User($code->get('user_id'));
			if($user->exists()){
				$APP->assign('user_data', $user->get_data(array('username','email','fullname','secure_q')));
				$APP->setPage('change_password');
			}else $APP->setMsg('Invalid recovery Link or User does not exist');
		}else $APP->setMsg('Invalid recovery link');
}
?>