<?php
//------------------------------------------------
// login check
//------------------------------------------------
function session_auth()
{
	global $USER, $APP;
	if (isset($USER) && is_object($USER) && $USER instanceof User) {
		return ($USER->exists() && $USER->is_loggedin());
	}
	return false;
}
function cross_domain_session()
{
	$some_name = session_name("some_name");
	session_set_cookie_params(0, '/', '.some_domain.com');
	session_start();
}
//to build filter while clause for mysql
function build_filter($filter, $table = '')
{
	global $APP;
	if ($table) $prefix = $table . '.';
	else $prefix = '';
	if (!is_array($filter)) return '';
	$arr = array();
	foreach ($filter as $key => $val) {
		if ($val == '') continue;
		if (preg_match('/^[a-z0-9_]+$/', $key)) {
			$arr[] = $prefix . $key . " = UNHEX('" . bin2hex($val) . "')";
			$APP->assign('filter_' . $key, $val);
		}
	}
	$result = implode(' AND ', $arr);
	return $result;
}
//to build search while clause for mysql
function build_search($q, $fields)
{
	global $APP;
	$APP->assign('search_q', $q);
	$q = DBRow::escape($q);
	if (!is_array($fields) && $fields) return $fields . " LIKE '%" . $q . "%'";
	if (!is_array($fields)) return '';
	$arr = array();
	foreach ($fields as $key) {
		if ($key == '') continue;
		if (preg_match('/^[a-z0-9_\.]+$/', $key)) $arr[] = $key . " LIKE '%" . $q . "%'";
	}
	$result = implode(' OR ', $arr);
	return $result;
}
function make_time($date, $hr = 0, $min = 0, $sec = 0, $date_delimiter = '-', $date_format = 'ymd')
{
	if (!$date) $date = date('Y' . $date_delimiter . 'm' . $date_delimiter . 'd');
	//check compatibility of date format
	if (strlen($date_format) != 3 || strpos($date_format, 'y') === false || strpos($date_format, 'm') === false || strpos($date_format, 'd') === false) $date_format = 'ymd';
	$arr = explode($date_delimiter, $date);
	$yr = $arr[strpos($date_format, 'y')];
	$month = $arr[strpos($date_format, 'm')];
	$day = $arr[strpos($date_format, 'd')];
	return mktime($hr, $min, $sec, $month, $day, $yr);
}
function array_order_by($data = NULL, $field = 'id', $order = 'asc')
{
	if (is_array($data)) {
		if (!defined('TEMPFIELD_TARGET')) define('TEMPFIELD_TARGET', $field);
		if (!defined('TEMPORDER_DIRECTION')) define('TEMPORDER_DIRECTION', $order);
		usort($data, function ($a, $b) {
			if (TEMPORDER_DIRECTION == 'desc') {
				return ($b['' . TEMPFIELD_TARGET . ''] - $a['' . TEMPFIELD_TARGET . '']) > 0;
			} else {
				return ($a['' . TEMPFIELD_TARGET . ''] - $b['' . TEMPFIELD_TARGET . '']) > 0;
			}
		});
		return $data;
	}
}
function array_multi_search($arr, $key, $val)
{
	$to_search = array_combine(array_keys($arr), array_column($arr, $key));
	return array_search($val, $to_search);
}
function array_search_key_value($arr, $key, $val)
{
	$index = array_multi_search($arr, $key, $val);
	if (is_numeric($index) || strval($index) != '') return @$arr[$index];
}
function convert_to_24hrs($hr, $am = 'am')
{
	if ($hr > 12) $h -= 12;
	switch ($am) {
		case 'pm':
			if ($hr == 12) return $hr;
			return $hr + 12;
			break;
		default: //if its am
			if ($hr == 12) return 0;
			return $hr;
	}
}
function get_day_range($date = null)
{ //Returns the range of time of a day from 0:00 to 23:59. accepts Date string of any format or unix time stamp as argument $date
	if ($date == null) $date = time();
	$date = is_numeric($date) ? $date : strtotime($date);
	$start = strtotime('today', $date);
	$end = strtotime('tomorrow', $date) - 1;
	$result = array('start' => $start, 'end' => $end);
	return $result;
}
function get_week_range($date = null)
{ //Returns the range of time of a week from 0:00 to 23:59. accepts Date string of any format or unix time stamp as argument $date
	if ($date == null) $date = time();
	$date = is_numeric($date) ? $date : strtotime($date);
	$start = strtotime(date('o-\\WW', $date));
	$end = strtotime('Monday next week', $start) - 1;
	$result = array('start' => $start, 'end' => $end);
	return $result;
}
function get_month_range($date = null)
{ //Returns the range of time of a month from 0:00 to 23:59. accepts Date string of any format or unix time stamp as argument $date
	if ($date == null) $date = time();
	$date = is_numeric($date) ? $date : strtotime($date);
	$start = strtotime('today', strtotime('first day of this month', $date));
	$end =  strtotime('today', strtotime('first day of next month', $date)) - 1;
	$result = array('start' => $start, 'end' => $end);
	return $result;
}
function get_year_range($date = null)
{ //Returns the range of time of a year from 0:00 to 23:59. accepts Date string of any format or unix time stamp as argument $date
	if ($date == null) $date = time();
	$date = is_numeric($date) ? $date : strtotime($date);
	$start = mktime(0, 0, 0, 1, 1, date('Y', $date));
	$end = mktime(0, 0, 0, 12, 32, date('Y', $date)) - 1;
	$result = array('start' => $start, 'end' => $end);
	return $result;
}
function fetch_text_from_html($source = '')
{
	if (!$source) return '';
	$dom = new DOMDocument();
	$result = '';
	if ($dom->loadHTML($source)) {
		foreach ($dom->getElementsByTagName('body') as $element) {
			//if ($element->nodeType != XML_TEXT_NODE) continue;
			if ($element->nodeValue) $result .= $element->nodeValue . PHP_EOL;
		}
	} else return '';
	//return $dom->nodeValue;
	return $result;
}
function issubmit()
{
	return isset($_POST['issubmit']) && $_POST['issubmit'];
}
function make_issubmit()
{
	return '<input type="hidden" name="issubmit" value="1" />';
}
//------------------------------------------------
// Require login
//------------------------------------------------
function require_login($user_level = '')
{
	global $APP, $USER;
	if (!$APP->isLoggedIn()) {
		if ($APP->isJSON()) {
			$APP->setMsg('Login is required to access this resource!', 'error');
			$APP->printOutput();
			exit;
		}
		$_SESSION['login_redirected'] = 1;
		redirect(HOME_DIR . 'login/', 1);
	} else {
		if ($USER->get('active') != 1) redirect(HOME_DIR . 'login/', 1);
		if ($user_level) {
			if (is_array($user_level)) {
				if (!in_array($USER->get('role'), $user_level)) {
					$_SESSION['access_denied'] = 1;
					redirect(HOME_DIR . '404');
				}
			} else {
				if ($USER->get('role') != $user_level) {
					$_SESSION['access_denied'] = 1;
					redirect(HOME_DIR . '404');
				}
			}
		}
	}
}

// function _exit()
// {
// 	global $DB;
// 	@$DB->close();
// 	exit;
// }
//------------------------------------------------
// Redirect user
//------------------------------------------------
function redirect($url, $request_uri = 0)
{
	global $APP;
	if ($request_uri && isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] && isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']) {
		$_SESSION['REQUEST_URI'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	} //echo('redirect from '. get_current_url(true).' to '.$url); exit;
	if ($APP->isJSON()) {
		$APP->assign('redirect', $url);
		$APP->printOutput();
		exit;
	}
	if (@headers_sent()) {
		echo '<div><center>Processing...</center></div><script>location.href="' . $url . '";</script>';
		exit;
	}
	header("location: $url", true, 302);
	exit;
}

function get_current_url($with_query = 1)
{
	return APP_PROTOCOL . $_SERVER['HTTP_HOST'] . ($with_query ? $_SERVER['REQUEST_URI'] : parse_url(APP_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

//------------------------------------------
// Generate random string
//------------------------------------------
function random_string($length = 32, $allcases = 1)
{
	$randstr = '';
	srand((float) microtime() * 1000000);

	if ($allcases)
		$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'j', 'h', 'k', 'm', 'n', 'o', 'q', 'p', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'J', 'H', 'K', 'L', 'M', 'N', 'Q', 'P', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	else
		$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'j', 'h', 'k', 'm', 'n', 'o', 'q', 'p', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9');

	$totalchars = count($chars);

	for ($i = 0; $i < $length; $i++) {
		$random = rand(0, $totalchars - 1);
		$randstr .= $chars[$random];
	}

	return $randstr;
}
//------------------------------------------
// Generate random string
//------------------------------------------
function random_numbers($length = 10)
{
	$randstr = '';
	srand((float) microtime() * 1000000);
	$chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

	$totalchars = count($chars);

	for ($i = 0; $i < $length; $i++) {
		$random = rand(0, $totalchars - 1);
		$randstr .= $chars[$random];
	}

	return $randstr;
}
function get_unique_auth_token_selector()
{
	$selector = random_string(12);
	$a = new DBRow('auth_tokens', 'id');
	$a->search('selector', $selector);
	if ($a->exists()) return get_unique_auth_token_selector();
	return $selector;
}
function generate_password_hash()
{
	$activate_db = new DBRow('password_hash', 'id');
	$result = random_string();
	if ($activate_db->exists_in_table('hash', $result)) return generate_password_hash();
	return $result;
}
function save_new_password_hash($id)
{
	$hash = generate_password_hash();
	$row = new DBRow('password_hash', 'id');
	$data = array(
		'hash' => $hash,
		'user_id' => $id,
		'activated' => 0
	);
	$row->set_data($data);
	if ($row->save()) {
		return $hash;
	} else {
		return save_new_activation_hash($id);
	}
}
function generate_activation_hash()
{
	$activate_db = new DBRow('account_activation_hash', 'id');
	$result = random_string();
	if ($activate_db->exists_in_table('hash', $result)) return generate_activation_hash();
	return $result;
}
function save_new_activation_hash($id)
{
	$hash = generate_activation_hash();
	$row = new DBRow('account_activation_hash', 'id');
	$data = array(
		'hash' => $hash,
		'user_id' => $id,
		'activated' => 0
	);
	$row->set_data($data);
	if ($row->save()) {
		return $hash;
	} else {
		return save_new_activation_hash($id);
	}
}
function save_new_activation_hash_code($id)
{
	$hash = generate_activation_hash();
	$code = random_numbers(6);
	$row = new DBRow('account_activation_hash', 'id');
	$data = array(
		'hash' => $hash,
		'user_id' => $id,
		'activated' => 0,
		'sms_code' => $code,
		'sms_expiry_date' => time()
	);
	$row->set_data($data);
	if ($row->save()) {
		return array('hash' => $hash, 'sms_code' => $code);
	} else {
		return save_new_activation_hash_code($id);
	}
}
/**
 * Echo a string (but only returns it so you can do as you wish with it)
 *
 * It bypasses XSS attacks by stripping all known XSS vulnerable tags
 * This is expected to be used everywhere you echo to the client or browser, basically when its not a JSON response
 *
 * @param string $string The text to strip and return
 * @param string $allow_tags Optional: if specified can contain the array of tags/attributes to allow. If just true the text is returned without stripping
 */
function _e($string, $allow_tags = false)
{
	if ($allow_tags === true) return $string;
	//handle allow some tags #TODO
	if (is_string($string)) return  htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	return $string;
}
// End function
if (!function_exists('hash_equals')) {

	/**
	 * Timing attack safe string comparison
	 *
	 * Compares two strings using the same time whether they're equal or not.
	 * This function should be used to mitigate timing attacks; for instance, when testing crypt() password hashes.
	 *
	 * @param string $known_string The string of known length to compare against
	 * @param string $user_string The user-supplied string
	 * @return boolean Returns TRUE when the two strings are equal, FALSE otherwise.
	 */
	function hash_equals($known_string, $user_string)
	{
		if (func_num_args() !== 2) {
			// handle wrong parameter count as the native implentation
			trigger_error('hash_equals() expects exactly 2 parameters, ' . func_num_args() . ' given', E_USER_WARNING);
			return null;
		}
		if (is_string($known_string) !== true) {
			trigger_error('hash_equals(): Expected known_string to be a string, ' . gettype($known_string) . ' given', E_USER_WARNING);
			return false;
		}
		$known_string_len = strlen($known_string);
		$user_string_type_error = 'hash_equals(): Expected user_string to be a string, ' . gettype($user_string) . ' given'; // prepare wrong type error message now to reduce the impact of string concatenation and the gettype call
		if (is_string($user_string) !== true) {
			trigger_error($user_string_type_error, E_USER_WARNING);
			// prevention of timing attacks might be still possible if we handle $user_string as a string of diffent length (the trigger_error() call increases the execution time a bit)
			$user_string_len = strlen($user_string);
			$user_string_len = $known_string_len + 1;
		} else {
			$user_string_len = $known_string_len + 1;
			$user_string_len = strlen($user_string);
		}
		if ($known_string_len !== $user_string_len) {
			$res = $known_string ^ $known_string; // use $known_string instead of $user_string to handle strings of diffrent length.
			$ret = 1; // set $ret to 1 to make sure false is returned
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;
		}
		for ($i = strlen($res) - 1; $i >= 0; $i--) {
			$ret |= ord($res[$i]);
		}
		return $ret === 0;
	}
}
//------------------------------------------
// Send remote request
//------------------------------------------
function send_remote_request($url, $path, $postfields = array(), $return_type = 'plain')
{
	$secure = substr($url, 0, 8) == 'https://' ? 1 : 0;

	$params = array();
	if (is_array($postfields)) {
		foreach ($postfields as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					$params[] = $k . '[' . $k2 . ']=' . urlencode($v2);
				}
			} else {
				$params[] = $k . '=' . urlencode($v);
			}
		}
	}
	$params = implode('&', $params);
	$request_length = strlen($params);

	if (function_exists('curl_init')) {
		$ch = @curl_init();
		@curl_setopt($ch, CURLOPT_URL, $url . $path);
		if (is_array($postfields)) {
			@curl_setopt($ch, CURLOPT_POST, true);
			@curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		//@curl_setopt($ch, CURLOPT_REFERER, $url.$path);
		@curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.2) Gecko/2008090514 Firefox/3.0.2');
		if ($secure) {
			//@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			//@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		@curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		@curl_setopt($ch, CURLOPT_HEADER, 0);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = @curl_exec($ch);
		if (curl_error($ch)) {
			//echo "error - ".curl_error($ch);exit;
		}
		@curl_close($ch);
	} else {
		$url = substr($url, ($secure ? 8 : 7));

		if (is_array($postfields)) {
			$header  = "POST $path HTTP/1.0\r\n";
		} else {
			$header  = "GET $path HTTP/1.0\r\n";
		}
		$header .= "Host: $url\r\n";
		$header .= "Cache-Control: no-cache\r\n";
		$header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.2) Gecko/2008090514 Firefox/3.0.2\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . $request_length . "\r\n";
		$header .= "Connection: Close\r\n\r\n";

		$fpointer = @fsockopen(($secure ? "ssl://" : "") . $url, ($secure ? 443 : 80), $errno, $errstr, 15);
		if ($fpointer) {
			$data = '';
			fwrite($fpointer, $header . $params);
			while (!feof($fpointer)) {
				$data .= fread($fpointer, 1024);
			}
			fclose($fpointer);

			$data = explode("\r\n\r\n", $data);
			$data = urldecode($data[1]);
		} else {
			//echo "error - ".$errstr;exit;
		}
	}

	if ($return_type == 'json') {
		return $data ? json_decode($data) : false;
	} else {
		return $data ? $data : false;
	}
}
// End function


//------------------------------------------
// URL Request
//------------------------------------------
function URLRequest($url_full, $protocol = "GET")
{
	$url = parse_url($url_full);
	$port = (empty($url['port'])) ? false : true;
	if (!$port) {
		if ($url['scheme'] == 'http') {
			$url['port'] = 80;
		} elseif ($url['scheme'] == 'https') {
			$url['port'] = 443;
		}
	}
	$url['query'] = empty($url['query']) ? '' : $url['query'];
	$url['path'] = empty($url['path']) ? '' : $url['path'];
	$url['protocol'] = $url['scheme'] . '://';

	if (function_exists('curl_init')) {
		if ($protocol == "GET") {
			$ch = curl_init($url['protocol'] . $url['host'] . $url['path'] . '?' . $url['query']);
			if ($ch) {
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				if ($port) curl_setopt($ch, CURLOPT_PORT, $url['port']);
				$content = curl_exec($ch);
				curl_close($ch);
			} else $content = '';
		} else {
			$ch = curl_init($url['protocol'] . $url['host'] . $url['path']);
			if ($ch) {
				//use curl if it exists for better speed
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $url['query']);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_USERAGENT, "SMS Portal Creator");
				if ($port) curl_setopt($ch, CURLOPT_PORT, $url['port']);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec($ch);
				var_dump($content);
				exit;
				curl_close($ch);
			} else $content = '';
		}
	} else if (function_exists('fsockopen')) {

		$eol = "\r\n";
		$h = "";
		$postdata_str = "";
		$getdata_str = "";
		if ($protocol == 'POST') {
			$h = "Content-Type: text/html" . $eol .
				"Content-Length: " . strlen($url['query']) . $eol;
			$postdata_str = $url['query'];
		} else	$getdata_str = "?" . $url['query'];

		$headers =  "$protocol " . $url['protocol'] . $url['host'] . $url['path'] . $getdata_str . " HTTP/1.0" . $eol .
			"Host: " . $url['host'] . $eol .
			"Referer: " . $url['protocol'] . $url['host'] . $url['path'] . $eol . $h .
			"Connection: Close" . $eol . $eol .
			$postdata_str;
		$fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 60);
		if ($fp) {
			fputs($fp, $headers);
			$content = '';
			while (!feof($fp)) {
				$content .= fgets($fp, 128);
			}
			fclose($fp);
			//removes headers
			$pattern = "/^.*\r\n\r\n/s";
			$content = preg_replace($pattern, '', $content);
		}
	} else {

		try {
			if ($protocol == "GET") return file_get_contents($url_full);
			else {
				$site = explode("?", $url_full, 2);
				$content = file_get_contents($site[0], false, stream_context_create(array('http' => array('method' => 'POST', 'header' => "Connection: close\r\nContent-Length: " . strlen($site[1]) . "\r\n", 'content' => $site[1]))));
			}
		} catch (Exception $g) {
			$content = "";
		}
	}

	return $content;
} //end of function URLRequest($url, $protocol="GET")
//------------------------------------------
// Send Email
//------------------------------------------
function send_email($to, $subject, $msg, $msg_html = '', $reply_to = '')
{
	if (!(defined('ALLOW_EMAIL') && ALLOW_EMAIL)) return;
	// require_once(GLOBAL_PATH . 'plugins/swiftmailer-master/lib/swift_required.php');
	require_once(GLOBAL_PATH . 'plugins/vendor/autoload.php');
	// Create the message
	$message = (new Swift_Message($subject))

		// Set the From address with an associative array
		->setFrom(array(ADMIN_EMAIL => ADMIN_NAME))
		->setReturnPath($reply_to ? $reply_to : ADMIN_EMAIL)
		->setSender(ADMIN_EMAIL)
		->setPriority(2)
		->setId(time() . '.' . uniqid('email') . '@' . ADMIN_DOMAIN)

		// Set the To addresses with an associative array
		->setTo($to)
		// Give it a body
		->setBody($msg);
	// And optionally an alternative body
	if ($msg_html) $message->addPart($msg_html, 'text/html');




	// Create the Transport
	if (defined('SMTP_ENCRYPT') && SMTP_ENCRYPT) {
		$transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT, SMTP_ENCRYPT))
			->setUsername(SMTP_USERNAME)
			->setPassword(SMTP_PASSWORD);
	} else {
		$transport = (new Swift_SmtpTransport(SMTP_HOST, SMTP_PORT))
			->setUsername(SMTP_USERNAME)
			->setPassword(SMTP_PASSWORD);
	}

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);

	// Send the message
	$result = $mailer->send($message);

	return $result;
}


//------------------------------------------
// Send template based email
//------------------------------------------
function send_email_template($sendto, $template, $replacements, $language = '')
{
	global $APP;
	//------------------------------------------------
	// Fetch email template
	//------------------------------------------------
	$email_row = new DBRow('email_templates', 'id');
	$email_row->set('title', $template);
	$email_row->search('title');
	if ($email_row->exists()) {
		//------------------------------------------------
		// Set email subject and body
		//------------------------------------------------
		$subject = $email_row->get('subject');
		$message = $email_row->get('message');
		$message_html = $email_row->get('message_html');
		if (substr($message_html, 0, 6) == 'file::') {
			$file = substr($message_html, 6);
			if (file_exists(INCLUDE_PATH . 'email_templates/' . $file)) {
				ob_start();
				include INCLUDE_PATH . 'email_templates/' . $file;
				$message_html = ob_get_contents();
				ob_end_clean();
			} else {
				$message_html = '';
			}
		}
	} else {
		$APP->setError('email_sending', 'Email template not found');
		return;
	}


	//------------------------------------------------
	// Replace email body with tagged values
	//------------------------------------------------
	foreach ($replacements as $key => $val) {
		$message = str_replace("{" . $key . "}", $val, $message);
		$message_html = str_replace("{" . $key . "}", $val, $message_html);
	}
	$message = str_replace("{website}", HOME_DIR, $message);
	$message_html = str_replace("{website}", '<a href="' . HOME_DIR . '">' . ADMIN_NAME . '</a>', $message_html);
	$message = str_replace("{referral_link}", HOME_DIR, $message);
	$message_html = str_replace("{referral_link}", '<a href="' . HOME_DIR . '">' . ADMIN_NAME . '</a>', $message_html);

	//echo "$subject, $message";
	//------------------------------------------------
	// Send email
	//------------------------------------------------
	//return $EMAIL->send('info@xmeventsng.com', 'XM Events', $sendto, $subject, $message, $message_html);
	//return mail::sendMail($sendto, $subject, $message_html);
	return send_email($sendto, $subject, $message, $message_html);
}
// End function
if (!function_exists('hash_equals')) {
	function hash_equals($str1, $str2)
	{
		if (strlen($str1) != strlen($str2)) {
			return false;
		} else {
			$res = $str1 ^ $str2;
			$ret = 0;
			for ($i = strlen($res) - 1; $i >= 0; $i--) {
				$ret |= ord($res[$i]);
			}
			return !$ret;
		}
	}
}

function notify_admins($subject, $message_html, $reply_to = '')
{
	global $DB;
	$query = "SELECT * FROM " . DB_PREFIX . "users WHERE role = 'admin'";
	$admins = $DB->get_query_set($query);
	$message = stripslashes($message_html);
	if (is_array($admins)) {
		foreach ($admins as $admin) {
			send_email(array($admin['email'] => $admin['fullname']), $subject, $message, $message_html, $reply_to);
		}
	}
}


function post_excerpt($content, $length = 40, $more = '...')
{
	$excerpt = strip_tags(trim($content));
	$words = str_word_count($excerpt, 2);
	if (count($words) > $length) {
		$words = array_slice($words, 0, $length, true);
		end($words);
		$position = key($words) + strlen(current($words));
		$excerpt = substr($excerpt, 0, $position) . $more;
	}
	return $excerpt;
}

function generate_new_jwt_token()
{
	$token = random_string(128);
	$jwt = new DBRow("user_sessions", "id");
	$jwt->search("token", hash('sha256', $token));
	if ($jwt->exists()) {
		return generate_new_jwt_token();
	}
	return $token;
}

function get_user_ip()
{
	return getenv('HTTP_CLIENT_IP') ?:
		getenv('REMOTE_ADDR') ?:
		getenv('HTTP_X_FORWARDED_FOR') ?:
		getenv('HTTP_X_FORWARDED') ?:
		getenv('HTTP_FORWARDED_FOR') ?:
		getenv('HTTP_FORWARDED');
}
