<?php 
	$APP->setTemplate('auth');
	$APP->setTitle('Account Login');
	if(session_auth()) {
		redirect(HOME_DIR . "dashboard");
	}
	if(issubmit()){
		$username = isset($_POST['username']) && $_POST['username']? $_POST['username'] : (isset($_POST['email']) && @$_POST['email'] ? @$_POST['email'] : '');
		$password = isset($_POST['password']) && $_POST['password']? $_POST['password'] : '';
		if($username && $password){
			login:
			$USER = new User();
			if($USER->login($username, $password)){
				$APP->setMsg('Login successful');
				if($USER->get('active') == 1){

					$url = '';
					if(isset($_GET['redirect']) && $_GET['redirect']){
						$url = $_GET['redirect'];
					}else{
						$url = isset($_SESSION['REQUEST_URI']) && $_SESSION['REQUEST_URI']? $_SESSION['REQUEST_URI'] : HOME_DIR . "dashboard";
						unset($_SESSION['REQUEST_URI']);
					}
					if(@$_POST['remember_me'] && 1 == 2){ // bypass
						$selector = get_unique_auth_token_selector();
						$token = random_string(64);
						$auth = new DBRow('auth_tokens','id');
						$token_hashed = hash('sha256',$token);
						$data = array();
						$data['selector'] = $selector;
						$data['token'] = $token_hashed;
						$data['user_id'] = $USER->get('id');
						$data['created_date'] = time();
						$auth->set_data($data);
						if($auth->save()){
							setcookie('sessionidtoken',$selector.$token, time() + User::COOKIE_DURATION,'/',$_SERVER['HTTP_HOST']);
						}
					}
					if($url) redirect($url);
				}else{
					$USER->logout();
					$step = $USER->get('activation_sent') ? 'activation' : 'activation_method';
					$APP->setSessionMsg('Your account is not active yet!');
					redirect(BASE_DIR.'register/account-activate/?username='.$USER->get('username').'&step='.$step);
				}
			}else{
				$APP->setMsg($APP->getError('login'), 'error');
			}
		}else{
			$APP->setMsg('Invalid parameters supplied', 'error');
		}
	}
	if(session_auth()){
		if($USER->get('active') != 1){
			$APP->setMsg('Your account has not been activated yet, <p>Please check your email and click on the activation link sent to you.</p>', 'warning');
		}else $APP->setMsg('You are already logged in!');
	}else{
		if(isset($_SESSION['login_redirected']) && $_SESSION['login_redirected']) $APP->setMsg('Please Login to continue request!');
		unset($_SESSION['login_redirected']);
	}
?>