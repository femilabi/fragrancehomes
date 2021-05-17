<?php
class DB
{
	protected $db_name = DB_NAME;
	protected $db_user = DB_USER;
	protected $db_pass = DB_PASS;
	protected $db_host = DB_HOST;
	private $result_id;
	private $con_id;

	private $log_error_file;
	private $log_errors = false;

	function __construct()
	{
		$this->connect();
		$this->set_log_error_file(dirname(SYS_PATH) . '/db_error.log');
		$this->set_log_errors(true);
	}

	public function connect()
	{
		global $DB;
		if (is_object($DB) && @$DB->get_con_id()) {
			$this->con_id = $DB->get_con_id();
			return $this->con_id;
		} else {
			$connection = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, '', 3306);
			mysqli_select_db($connection, $this->db_name);
			$this->con_id = $connection;
			return $connection;
		}
	}
	public function close()
	{
		mysqli_close($this->con_id);
		$this->con_id = NULL;
		$this->result_id = NULL;
	}
	public function set_params($arr)
	{
		if (!is_array($arr)) return;
		$con_changed = false;
		if (in_array('DB_NAME', array_keys($arr)) && $arr['DB_NAME']) {
			$this->db_name = $arr['DB_NAME'];
			$con_changed = true;
		}
		if (in_array('DB_USER', array_keys($arr)) && $arr['DB_USER']) {
			$this->db_user = $arr['DB_USER'];
			$con_changed = true;
		}
		if (in_array('DB_PASS', array_keys($arr)) && $arr['DB_PASS']) {
			$this->db_pass = $arr['DB_PASS'];
			$con_changed = true;
		}
		if (in_array('DB_HOST', array_keys($arr)) && $arr['DB_HOST']) {
			$this->db_host = $arr['DB_HOST'];
			$con_changed = true;
		}
		if ($con_changed) {
			$this->close();
			$this->connect();
		}
		return $con_changed;
	}
	public function get_con_id()
	{
		return $this->con_id;
	}
	public function insert_id()
	{
		return mysqli_insert_id($this->con_id);
	}
	public function query($query)
	{
		$result = mysqli_query($this->con_id, $query);
		$this->result_id = $result;
		$this->log_sql_error();
		return $result;
	}
	private function log_sql_error()
	{
		if ($this->log_errors && $this->log_error_file) {
			$error  = $this->get_error();
			if ($error) {
				$error_string = date('Y-m-d H:i:s') . ', ' . @get_current_url() . ', ' . $error . PHP_EOL;
				@file_put_contents($this->log_error_file, $error_string, FILE_APPEND);
			}
		}
	}
	public function q($query)
	{
		$result = mysqli_query($this->con_id, $query);
		$this->result_id = $result;
		$this->log_sql_error();

		$arr = array();
		if ($result = mysqli_store_result($this->con_id) || mysqli_num_rows($result)) {
			if (mysqli_num_rows($result)) {
				while ($row = mysqli_fetch_array($result, true)) {
					array_push($arr, $row);
				}
			}
			mysqli_free_result($result);
		} elseif (mysqli_insert_id($this->con_id)) {
			$arr = mysqli_insert_id($this->con_id);
		} elseif (mysqli_affected_rows($this->con_id)) {
			$arr = mysqli_affected_rows($this->con_id);
		} else {
			$arr = false;
		}

		return $arr;
	}
	public function multi_query($query)
	{
		if (is_array($query)) $query = implode('; ', $query);
		$results = array();
		if (mysqli_multi_query($this->con_id, $query)) {
			do {
				/* store first result set */
				$arr = array();
				if ($result = mysqli_store_result($this->con_id)) {
					if (mysqli_num_rows($result)) {
						while ($row = mysqli_fetch_array($result, true)) {
							array_push($arr, $row);
						}
					}
					mysqli_free_result($result);
				} elseif (mysqli_insert_id($this->con_id)) {
					$arr = mysqli_insert_id($this->con_id);
				} elseif (mysqli_affected_rows($this->con_id)) {
					$arr = mysqli_affected_rows($this->con_id);
				} else {
					$arr = false;
				}
				$results[] = $arr;
			} while (mysqli_more_results($this->con_id) && mysqli_next_result($this->con_id));
		}
		return $results;
	}
	public function num_rows()
	{
		return mysqli_num_rows($this->result_id);
	}
	public function affected_rows()
	{
		return mysqli_affected_rows($this->con_id);
	}
	public function get_row_set($res_id, $single_row = false)
	{
		if (!$this->num_rows($res_id)) return [];
		if ($single_row) return mysqli_fetch_array($res_id, true);

		$arr = array();
		while ($row = mysqli_fetch_array($res_id, true)) {
			array_push($arr, $row);
		}
		return $arr;
	}
	public function get_query_set($query, $single_row = false)
	{
		$result = $this->query($query);
		if ($single_row) return $this->get_row_set($result, true);
		return $this->get_row_set($result);
	}
	public function fetch_array($id, $assoc = true)
	{
		return mysqli_fetch_array($id, $assoc);
	}
	public function fetch_object($id)
	{
		return mysqli_fetch_object($id);
	}
	public function get_error()
	{
		return mysqli_error($this->con_id);
	}
	public static function qescape($param, $strip_tags = false)
	{
		$result = $strip_tags ? htmlspecialchars($param, ENT_QUOTES, 'UTF-8') : $param;
		return "UNHEX('" . bin2hex($result) . "')";
	}
	public static function sanitize_data($data)
	{
		if (is_string($data)) return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		if (is_array($data)) return array_map(['DB', 'sanitize_data'], $data);
		return $data;
	}
	function set_log_errors($val)
	{
		$this->log_errors = boolval($val);
	}
	function get_log_errors()
	{
		return $this->log_errors;
	}
	function set_log_error_file($val)
	{
		$this->log_error_file = $val;
	}
	function get_log_error_file()
	{
		return $this->log_error_file;
	}
}
