<?php
class DBRow
{
	private $id;
	private $id_name;

	private $table;
	private $columns = array();
	private $data = array();
	private $exists = false;

	private $db;
	private $db_prefix = DB_PREFIX;

	private $strict_mode = false;
	private $updated_fields = array();

	function __construct($table = '', $id_name = '', $id = 0)
	{
		if (defined('DB_STRICT_MODE') && DB_STRICT_MODE) $this->strict_mode = true;
		$this->db = new DB();
		if (isset($table) && $table) {
			$this->set_table($table);
		}
		if (isset($id_name) && $id_name) {
			$this->set_id_name($id_name);
		}
		if (isset($id) && $id) {
			$this->set_id($id);
		}
	}
	public function close()
	{
		$this->db->close();
	}
	public function set_params($arr)
	{
		if (!is_array($arr)) return;
		$con_changed = $this->db->set_params($arr);
		if (in_array('DB_PREFIX', array_keys($arr)) && $arr['DB_PREFIX']) {
			$this->db_prefix = $arr['DB_PREFIX'];
			$con_changed = true;
		}
		if ($con_changed) {
			$this->refresh();
		}
	}
	function initialize()
	{
		$col = $this->initialize_columns();
		$dat = $this->initialize_data();
		return $col && $dat;
	}
	function refresh()
	{
		return $this->initialize();
	}
	private function initialize_columns()
	{
		if ($this->table && $this->id_name) {
			//$data = $this->db->get_query_set("SELECT * FROM $this->table LIMIT 1", true);
			$data = $this->db->get_query_set("DESCRIBE $this->table");
			if ($data && is_array($data)) {
				$columns = array();
				foreach ($data as $row) {
					if ($row['Field'] != $this->id_name) $columns[] = $row['Field'];
				}
				if ($columns && is_array($columns)) {
					$this->columns = $columns;
					return true;
				}
			}
		}
		return false;
	}
	private function initialize_data()
	{
		if ($this->table && $this->id_name && $this->id) {
			$query = "SELECT * FROM $this->table WHERE $this->id_name = '$this->id'";
			$data = $this->db->get_query_set($query, true);
			if ($data && is_array($data)) {
				foreach ($data as $key => $val) {
					$this->_set($key, $val);
				}
				$this->exists = true;
				return true;
			}
		}
		$this->exists = false;
		return false;
	}
	function save()
	{
		if ($this->exists()) {
			$set = array();
			$params = array();
			foreach ($this->data as $key => $value) {
				if ((!$this->strict_mode || in_array($key, $this->updated_fields)) && (in_array($key, $this->columns) && $key != $this->id_name)) {
					//$set[] = "$key = ?"; // replace ? with UNHEX('".bin2hex($value)."')
					$set[] = "$key = UNHEX('" . bin2hex($value) . "')"; //comment for parametirized
					$params[] = $value;
				}
			}
			$types = str_pad('', count($params), 's');
			if (!count($set)) return false;
			$set_statement = implode(', ', $set);
			$query = "UPDATE $this->table SET $set_statement WHERE $this->id_name = $this->id LIMIT 1";
			//Parametirized
			//$con = $this->db->get_con_id();
			//$stmt = $con->prepare($query);
			//$stmt->bind_param($types, $params[0], $params[1]);
			//$stmt->execute();
			//return $stmt->get_result();

			$result = $this->db->query($query); //comment for parametirized
			return $result; //comment for parametirized
		} else {
			$keys = array();
			$values = array();
			if ($this->strict_mode) {
				$keys[] = $this->id_name;
				$values[] = 0;
			}
			if (!is_array($this->columns)) return;
			foreach ($this->columns as $key) {
				if ($key == $this->id_name) {
					//$keys[]= $key;
					//$values[] = 0;
				} else {
					if (!$this->strict_mode || isset($this->data[$key])) {
						$keys[] = $key;
						$val = is_numeric(@$this->data[$key]) ? $this->data[$key] + 0 : @$this->data[$key];
						//$values[] = is_float($val) || is_int($val)? $val : "UNHEX('".bin2hex(@$this->data[$key])."')";
						$values[] = "UNHEX('" . bin2hex(@$this->data[$key]) . "')";
					}
				}
			}
			$keys_statement = implode(', ', $keys);
			$values_statement = implode(', ', $values);
			$result = $this->db->query("INSERT INTO $this->table (" . $keys_statement . ") VALUES (" . $values_statement . ")");
			$id = $this->db->insert_id($result);
			$this->id = $id;
			$this->exists = true;
			return $id;
		}
		return false;
	}
	// function save()
	// {
	// 	if ($this->exists()) {
	// 		$set = array();
	// 		$params = array();
	// 		foreach ($this->data as $key => $value) {
	// 			if (in_array($key, $this->columns) && $key != $this->id_name) {
	// 				//$set[] = "$key = ?"; // replace ? with UNHEX('".bin2hex($value)."')
	// 				$set[] = "$key = UNHEX('" . bin2hex($value) . "')"; //comment for parametirized
	// 				$params[] = $value;
	// 			}
	// 		}
	// 		$types = str_pad('', count($params), 's');
	// 		if (!count($set)) return false;
	// 		$set_statement = implode(', ', $set);
	// 		$query = "UPDATE $this->table SET $set_statement WHERE $this->id_name = $this->id LIMIT 1";
	// 		//Parametirized
	// 		//$con = $this->db->get_con_id();
	// 		//$stmt = $con->prepare($query);
	// 		//$stmt->bind_param($types, $params[0], $params[1]);
	// 		//$stmt->execute();
	// 		//return $stmt->get_result();

	// 		$result = $this->db->query($query); //comment for parametirized
	// 		return $result; //comment for parametirized
	// 	} else {
	// 		$keys = array();
	// 		$values = array();
	// 		$keys[] = $this->id_name;
	// 		$values[] = 0;
	// 		if (!is_array($this->columns)) return;
	// 		foreach ($this->columns as $key) {
	// 			if ($key == $this->id_name) {
	// 				//$keys[]= $key;
	// 				//$values[] = 0;
	// 			} else {
	// 				$keys[] = $key;
	// 				$val = is_numeric(@$this->data[$key]) ? $this->data[$key] + 0 : @$this->data[$key];
	// 				//$values[] = is_float($val) || is_int($val)? $val : "UNHEX('".bin2hex(@$this->data[$key])."')";
	// 				$values[] = "UNHEX('" . bin2hex(@$this->data[$key]) . "')";
	// 			}
	// 		}
	// 		$keys_statement = implode(', ', $keys);
	// 		$values_statement = implode(', ', $values);
	// 		$result = $this->db->query("INSERT INTO $this->table (" . $keys_statement . ") VALUES (" . $values_statement . ")");
	// 		$id = $this->db->insert_id($result);
	// 		$this->id = $id;
	// 		$this->exists = true;
	// 		return $id;
	// 	}
	// 	return false;
	// }
	static function escape($val)
	{
		global $DB;
		$input = $val;
		//$input = addslashes($input);
		$input = mysqli_real_escape_string($DB->get_con_id(), $input);

		//if (get_magic_quotes_gpc()) {
		//  $input = stripslashes($input);
		//}
		//$input = mysqli_real_escape_string($this->db->connect(), $input);
		return $input;
	}
	static function un_escape($val)
	{
		$input = $val;
		//$input = addslashes($val);
		return $input;
	}
	function exists_in_table($field, $val)
	{
		$val = "UNHEX('" . bin2hex($val) . "')";
		if (!in_array($field, $this->columns)) return;
		$data = $this->db->get_query_set("SELECT * FROM " . $this->table . " WHERE $field = $val");
		if (is_array($data)) return count($data);
		return false;
	}
	function search($with_field, $val = null)
	{
		if ($this->table && $this->id_name && in_array($with_field, $this->columns) && (isset($this->data[$with_field]) || isset($val))) {
			$data = $this->db->get_query_set("SELECT * FROM " . $this->table . " WHERE $with_field = UNHEX('" . (isset($val) ? bin2hex($val) : bin2hex($this->data[$with_field])) . "')");
			if (is_array($data) && isset($data[0][$this->id_name])) {
				$this->set_id($data[0][$this->id_name]);
				return 1;
			}
		}
		return;
	}
	function save_to($to_id)
	{
		if (!is_numeric($to_id)) return false;
		$set = array();
		foreach ($this->data as $key => $value) {
			if (in_array($key, $this->columns)) {
				$set[] = "$key = UNHEX('" . bin2hex($value) . "')";
			}
		}
		if (!count($set)) return false;
		$set_statement = implode(', ', $set);
		$result = $this->db->query("UPDATE $this->table SET $set_statement WHERE $this->id_name = '$to_id' LIMIT 1");
		return $result;
	}
	function delete()
	{
		if ($this->exists()) {
			$this->db->query("DELETE FROM $this->table WHERE $this->id_name = '" . $this->id . "' LIMIT 1");
			return true;
		}
		return false;
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
	function set($key, $value)
	{
		if ($key != $this->id_name) {
			$this->data[$key] = $value;
			if (!in_array($key, $this->updated_fields)) $this->updated_fields[] = $key;
		}
	}
	private function _set($key, $value)
	{
		if ($key != $this->id_name) {
			$this->data[$key] = $value;
		}
	}

	//Meta functions requires th meta field with type text
	public function get_meta($key)
	{
		$meta = @json_decode($this->get('meta'), true);
		if (is_array($meta) && isset($meta[$key])) return $meta[$key];
		return '';
	}
	public function set_meta($key, $val)
	{
		$meta = @json_decode($this->get('meta'), true);
		$meta[$key] = $val;
		$this->set('meta', json_encode($meta));
		$this->save();
	}
	public function get_metas()
	{
		$meta = @json_decode($this->get('meta'), true);
		return $meta;
	}
	public function delete_meta($key)
	{
		$meta = @json_decode($this->get('meta'), true);
		if (is_array($meta) && isset($meta[$key])) {
			unset($meta[$key]);
			$this->set('meta', json_encode($meta));
			$this->save();
		}
	}
	public function has_meta($key)
	{
		$meta = @json_decode($this->get('meta'), true);
		return (is_array($meta) && isset($meta[$key]));
	}

	function exists()
	{
		return ($this->table && $this->id_name && $this->id && $this->exists);
	}

	function set_table($table)
	{
		if ($table) $this->table = (substr($table, 0, strlen($this->db_prefix)) == $this->db_prefix) ? $table : $this->db_prefix . $table;
		$this->initialize();
	}
	function set_id($id)
	{
		if ($id && is_numeric($id)) $this->id = $id;
		$this->initialize_data();
	}
	function set_id_name($id_name)
	{
		if ($id_name) $this->id_name = $id_name;
		$this->initialize();
	}

	function get($key)
	{
		if (isset($this->data[$key])) return $this->data[$key];
		if ($key == $this->id_name) return $this->id;
		return '';
	}
	function get_escaped($key)
	{
		if (isset($this->data[$key])) return $this->escape($this->data[$key]);
		if ($key == $this->id_name) return $this->id;
		return '';
	}
	function get_array($arr)
	{
		$result = array();
		foreach ($arr as $key) {
			$result[$key] = $this->get($key);
		}
		return $result;
	}
	function get_data($only_fields = '')
	{
		$result = array();
		if (!is_array($this->data)) return '';
		if (is_array($only_fields)) {
			foreach ($only_fields as $field) {
				$result[$field] = $this->get($field);
			}
		} else {
			foreach ($this->data as $key => $val) {
				$result[$key] = $this->get($key);
			}
		}
		$result[$this->id_name] = $this->id;
		return $result;
	}
	function get_id()
	{
		return $this->id;
	}
	function get_error()
	{
		return $this->db->get_error();
	}
}
