<?php
$APP->setTitle("Create New Account");
//load recaptcha plugin
//require_once(GLOBAL_PATH."plugins/recaptcha/recaptchalib.php");
//if(session_auth()) redirect(HOME_DIR."login");
if (@$_GET["inframe"] == "true") $APP->setTemplate("inframe");
$ref = @$_POST["form"]["ref"] ? $_POST["form"]["ref"] : (@$_GET["ref"] ? $_GET["ref"] : @$_SESSION["ref"]);
$referrer = new User();
$ref_wanted = array("fullname", "email");
if ($ref) {
	$referrer->load($ref);
	if ($referrer->exists()) {
		$_SESSION["ref"] = $referrer->get("email");
		$APP->assign("referrer_data", $referrer->get_data($ref_wanted));
	}
}
if (issubmit()) {
	if (!validate_recaptcha()) {
		// What happens when the CAPTCHA was entered incorrectly
		// $APP->setMsg("Error! Captcha check not passed. Please try again.","error");
		// return;
	}
	$data = @$_POST;
	$data["username"] = $data["phone"];
	if (@$data["ref"]) $referrer->load($ref);
	$APP->assign("referrer_data", $referrer->get_data($ref_wanted));
	if (!is_array($data)) return;
	$APP->assign("form_data", $data);
	if (validate_data($data)) {
		$USER = new User();
		if ($USER->username_exists($data["username"])) {
			$APP->setMsg("Username already exists, Please choose a different username to continue", "error");
		} elseif ($USER->email_exists($data["email"])) {
			$APP->setMsg('The email you provided has already been used by another user, Please choose a different email to continue<br/>If you are the owner of the email try to <a href="login/">login</a> with the email or click the <a href="forgot-password/">forgot password</a> link to retrieve your account password.', "error");
		} elseif ($USER->exists_in_table("phone", $data["phone"])) {
			$APP->setMsg('The mobile number you provided has already been used by another user, Please choose a different phone number to continue<br/>If you are the owner of the number try to <a href="login/">login</a> with the number or click the <a href="forgot-password/">forgot password</a> link to retrieve your account password.', "error");
		} else {
			$data["active"] = 1;
			$data["seen_last_news"] = 1;
			$data["activation_sent"] = 0;
			$data["stage"] = 0;
			$USER->set("mentor_id", 1);
			if ($referrer->exists()) {
				$USER->set("referrer_id", $referrer->get_id());
			} else {
				$USER->set("referrer_id", 1);
			}
			// $data["secure_answer"] = trim($data["secure_answer"]);

			$USER->set_data($data, array("username", "password", "email", "fullname", "phone"));
			if ($USER->save()) {
				unset($_SESSION["ref"]);
				$APP->setSessionMsg("Congratulations, Your account has been successfully created!", "success");
				redirect(HOME_DIR . "account-activate/?username=" . $USER->get("username") . "&step=activation_method&send_email=1");
			}
		}
	}
}

function validate_data($data)
{
	global $APP;
	$err = 0;
	$names = explode(" ", preg_replace("/\s{2,}/", " ", @$data["fullname"]));
	if (count($names) > 5) {
		$APP->setError("names", "Names supplied should not be more than 3");
		$err = 1;
	}
	for ($i = 0; $i < count($names); $i++) {
		if (!isValidName($names[$i])) {
			//$APP->setError("name", "Please enter valid names");
			//$err = 1;
		}
	}
	if (!(isset($data["username"]) && isset($data["fullname"]) && isset($data["email"]) && isset($data["password"]) && isset($data["password2"]))) {
		$APP->setError("fields", "Please ensure all required fields (with asterisks \"*\") are filled");
		$err = 1;
	}
	if (!isValidName($data["username"])) {
		//$APP->setError("username", "Please enter a valid username");
		//$err = 1;
	}
	if (!($data["phone"])) {
		$APP->setError("username", "Please enter a valid phone number");
		$err = 1;
	}
	$pass = $data["password"];
	if (strlen($pass) < 6) {
		$APP->setError("password", "Password must be minimum of 6 characters");
		$err = 1;
	}
	if ($data["password"] != $data["password2"]) {
		$APP->setError("password2", "Passwords do not match");
		$err = 1;
	}
	if (!isValidEmail($data["email"])) {
		$APP->setError("email", "Please enter a valid email address");
		$err = 1;
	}
	// $country = new DBRow("countries", "id", $data["country_id"]);
	// if(!($country->exists())){
	// 	$APP->setError("country", "Please select valid country");
	// 	$err = 1;
	// }
	if ($err) return;
	return 1;
}
