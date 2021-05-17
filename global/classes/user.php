<?php
class User
{
	private $db;
	private $dbrow;
	private $role;
	private $entity_roles = [];
	protected $readonlys = array('username', 'password');
	protected $table = 'users';

	private $error;
	const COOKIE_DURATION = 2592000; //30 days

	function __construct($id = '')
	{
		$this->db = new DB();
		if ($id) {
			if (is_numeric($id)) {
				$this->dbrow = new DBRow($this->table, 'id', $id);
			} else {
				$this->dbrow = new DBRow($this->table, 'id');
				$this->load($id);
			}
		} else {
			$this->dbrow = new DBRow($this->table, 'id');
		}
	}
	private function reload()
	{
		if ($this->get('id')) $this->dbrow->set_id($this->get('id'));
		$this->role = null;
	}
	function set_data($data, $only_fields = '')
	{
		if ($data && is_array($data)) {
			if (is_array($only_fields)) {
				foreach ($data as $key => $value) {
					if (in_array($key, $only_fields)) $this->set($key, $value);
				}
			} else {
				foreach ($data as $key => $value) {
					$this->set($key, $value);
				}
			}
		}
	}
	public function set($key, $value)
	{
		if (!in_array($key, $this->readonlys)) $this->dbrow->set($key, $value);
		if ($key == 'username' && !$this->exists()) $this->dbrow->set($key, strtolower($value));
		if ($key == 'password' && !$this->exists()) $this->dbrow->set($key, self::hash_password($value));
	}
	public function save()
	{
		global $APP;
		$result = 0;
		if ($this->exists()) {
			$result = $this->dbrow->save();
		} else {
			if ($this->username_exists($this->get('username'))) {
				$APP->setMsg('Username already exists, Please choose a different username to continue', 'error');
				return;
			} elseif ($this->email_exists($this->get('email'))) {
				$APP->setMsg('The email you provided has already been used by another user, Please choose a different email to continue', 'error');
				return;
			} else {
				$time = time();
				$this->dbrow->set('joindate', $time);
				$this->dbrow->set('lastvisitdate', $time);
				$this->dbrow->set('role', 'user');
				$result = $this->dbrow->save();
			}
		}
		$this->role = null;
		return $result;
	}
	function get_error()
	{
		return $this->dbrow->get_error();
	}
	public function change_password($password, $changer_password = '')
	{
		if (isset($changer_password) && $changer_password) {
			if (isset($_SESSION[USER_SESSION_HOLDER]['id'])) {
				$u = new User($_SESSION[USER_SESSION_HOLDER]['id']);
				if ($u->confirm_password($changer_password) && ($u->get('role') == 'admin' || $u->get('id') == $this->get('id'))) {
					$this->dbrow->set('password', self::hash_password($password));
					$this->dbrow->save();
					return true;
				} else return false;
			} else return false;
		}
		return false;
	}
	public function login($username, $password)
	{
		global $APP;
		if ($this->load($username)) {
			if ($this->confirm_password($password)) {
				$this->implement_login();
				return true;
			} else {
				$this->error = 'Invalid Password';
				$APP->setError('login', 'Invalid Password');
				return false;
			}
		} else {
			$this->error = 'Invalid Username/Email';
			$APP->setError('login', 'Invalid Username/Email');
			return false;
		}
		return false;
	}
	public function implement_login()
	{
		if ($this->get('active') == 1) $this->initialize_session();
		$this->dbrow->set('lastvisitdate', time());
		if (function_exists('getUserIP')) {
			$ip = getUserIP();
		} else {
			$ip = getenv('HTTP_CLIENT_IP') ?:
				getenv('REMOTE_ADDR') ?:
				getenv('HTTP_X_FORWARDED_FOR') ?:
				getenv('HTTP_X_FORWARDED') ?:
				getenv('HTTP_FORWARDED_FOR') ?:
				getenv('HTTP_FORWARDED');
		}
		$this->dbrow->set('last_ip', $ip);
		if ($this->dbrow->get('reg_ip') == '') $this->dbrow->set('reg_ip', $ip);
		$this->dbrow->set('online', 1);
		$this->dbrow->save();
		return true;
	}
	public function cookie_login($token)
	{
		if (strlen($token) > 23) {
			$selector = substr($token, 0, 12);
			$validator = substr($token, 12);
			$auth = new DBRow('auth_tokens', 'id');
			$auth->search('selector', $selector);
			$validator_hashed = hash('sha256', $validator);
			if ($auth->exists() && (time() - $auth->get('created_date')) < self::COOKIE_DURATION && strlen($validator_hashed) === strlen($auth->get('token'))) {
				if (hash_equals($auth->get('token'), $validator_hashed)) {
					$this->dbrow = new DBRow($this->table, 'id', $auth->get('user_id'));
					$this->implement_login();
					return true;
				} else $auth->delete();
			}
		}
		//if this line executes then login failed
		setcookie('sessionidtoken', '', 1, '/', $_SERVER['HTTP_HOST']);
	}
	public function load($username)
	{
		$this->role = null;
		return $this->dbrow->search('username', $username) ? 1 : $this->dbrow->search('email', $username);
	}
	function username_exists($username)
	{
		$this->role = null;
		return $this->dbrow->exists_in_table('username', $username);
	}
	function email_exists($email)
	{
		return $this->dbrow->exists_in_table('email', $email);
	}
	function exists_in_table($field, $val)
	{
		return $this->dbrow->exists_in_table($field, $val);
	}
	function activate()
	{
		if (!$this->exists()) return;
		$this->dbrow->set('active', 1);
		return $this->save();
	}
	private function initialize_session()
	{
		$_SESSION['loggedin'] = true;
		$_SESSION[USER_SESSION_HOLDER] = $this->get_data();
	}
	public function logout()
	{
		$this->dbrow->set('online', 0);
		$this->save();
		$_SESSION[USER_SESSION_HOLDER] = NULL;
		$_SESSION['loggedin'] = NULL;
		unset($_SESSION[USER_SESSION_HOLDER]);
		unset($_SESSION['loggedin']);
		//delete cookie
		if (!empty($_COOKIE['sessionidtoken']) && strlen($_COOKIE['sessionidtoken']) > 23) {
			$token = $_COOKIE['sessionidtoken'];
			$selector = substr($token, 0, 12);
			$auth = new DBRow('auth_tokens', 'id');
			$auth->search('selector', $selector);
			setcookie('sessionidtoken', '', 1, '/', $_SERVER['HTTP_HOST']);
			if ($auth->exists()) $auth->delete();
		}
		return true;
	}
	public function is_loggedin()
	{
		return (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && isset($_SESSION[USER_SESSION_HOLDER]['id']) && ($_SESSION[USER_SESSION_HOLDER]['id'] == $this->get('id')) && isset($_SESSION[USER_SESSION_HOLDER]['username']) && $_SESSION[USER_SESSION_HOLDER]['username'] == $this->get('username'));
	}
	public function isonline()
	{
		return $this->get('online');
	}
	public function get_data($only_fields = '')
	{
		$data = $this->dbrow->get_data($only_fields);
		if (!empty($data['password'])) $data['password'] = 'xxx';
		return $data;
	}
	public function get($key)
	{
		$data = $this->get_data();
		if (isset($data[$key])) return $data[$key];
		return '';
	}
	public function exists()
	{
		return $this->dbrow->exists();
	}
	public function delete($deleter_pass)
	{
		if (isset($_SESSION[USER_SESSION_HOLDER]['id'])) {
			$u = new User($_SESSION[USER_SESSION_HOLDER]['id']);
			if ($u->confirm_password($deleter_pass) && $u->get('role') == 'admin') {
				$this->dbrow->delete();
				return true;
			} else return false;
		} else return false;
	}

	public static function hash_password($password)
	{
		return password_hash(
			base64_encode(
				hash('sha384', $password, true)
			),
			PASSWORD_DEFAULT
		);
	}
	//Meta functions
	public function get_meta($key)
	{
		$meta = @json_decode($this->dbrow->get('meta'), true);
		if (is_array($meta) && isset($meta[$key])) return $meta[$key];
		return '';
	}
	public function set_meta($key, $val)
	{
		$meta = @json_decode($this->dbrow->get('meta'), true);
		$meta[$key] = $val;
		$this->dbrow->set('meta', json_encode($meta));
		$this->save();
	}
	public function get_metas()
	{
		$meta = @json_decode($this->dbrow->get('meta'), true);
		return $meta;
	}
	public function delete_meta($key)
	{
		$meta = @json_decode($this->dbrow->get('meta'), true);
		if (is_array($meta) && isset($meta[$key])) {
			unset($meta[$key]);
			$this->dbrow->set('meta', json_encode($meta));
			$this->save();
		}
	}
	public function has_meta($key)
	{
		$meta = @json_decode($this->dbrow->get('meta'), true);
		return (is_array($meta) && isset($meta[$key]));
	}

	function confirm_password($password)
	{
		if (!$this->exists()) return false;
		//return ($this->dbrow->get('password') == self::hash_password($password));
		return password_verify(
			base64_encode(
				hash('sha384', $password, true)
			),
			$this->dbrow->get('password')
		);
	}
	function get_id()
	{
		return $this->dbrow->get_id();
	}

	//permissions functions (requires roles table)
	function get_permissions()
	{
		if (!$this->get('role_id')) return;
		if (!(is_object($this->role) && $this->role->exists())) $this->role = new DBRow('roles', 'id', $this->get('role_id'));
		return $this->role->get_metas();
	}
	function get_entity_roles()
	{
		global $DB;
		if ($this->entity_roles) return $this->entity_roles;
		$entity_roles = $DB->get_query_set("
            SELECT er.*, r.meta
			FROM " . DB_PREFIX . "entity_roles AS er, 
				" . DB_PREFIX . "roles AS r
			WHERE er.user_id = " . $this->get_id() . " 
				AND r.id = er.role_id
                AND er.active = 1
        ");

		foreach ($entity_roles as $er) {
			if (!(isset($this->entity_roles[$er["entity"]]) && is_array($this->entity_roles[$er["entity"]]))) {
				$this->entity_roles[$er["entity"]] = [];
			}
			$this->entity_roles[$er["entity"]][] = $er;
		}

		foreach ($this->entity_roles as $key => $er) {
			$this->entity_roles[$key] = array_combine(array_column($this->entity_roles[$key], "entity_id"), $this->entity_roles[$key]);
		}

		return $this->entity_roles;
	}

	function can($permission, $entity = "", $entity_id = "")
	{
		if ($entity && $entity_id) {
			$entity_roles = $this->get_entity_roles();
			if (@$entity_roles[$entity][$entity_id]) {
				$permissions = json_decode($entity_roles[$entity][$entity_id]["meta"]);
				return (is_array($permissions) && in_array($permission, $permissions));
			}
			return false;
		}

		$permissions = $this->get_permissions();
		return (is_array($permissions) && in_array($permission, $permissions)) || $this->get_meta("can_" . $permission);
	}
}
