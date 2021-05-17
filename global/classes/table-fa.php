<?php
class Table
{
	private $columns = array();
	private $data = array();
	private $options = array();
	private $links = array();
	private $editables = array();
	private $search = array();
	private $sort = array();
	private $choice = array();
	private $column_classes = array();
	private $row_attributes = array();
	private $table_class = '';

	private $date_columns = array();
	private $date_default = 'D, d M, Y h:iA';

	private $empty_text = 'Result is empty...';
	private $striped = true;
	private $bordered = true;
	private $hover = true;

	private $ajax_src;

	public function set_columns($columns)
	{
		if (is_array($columns)) {
			$this->columns = $columns;
			return true;
		}
		return false;
	}
	public function set_data($data)
	{
		if (is_array($data)) {
			$this->data = $data;
			return true;
		}
		return false;
	}
	public function set_options($options)
	{
		if (is_array($options)) {
			$this->options = $options;
			return true;
		}
		return false;
	}
	public function set_links($links)
	{
		if (is_array($links)) {
			$this->links = $links;
			return true;
		}
		return false;
	}
	public function set_editables($editables)
	{
		if (is_array($editables)) {
			$this->editables = $editables;
			return true;
		}
		return false;
	}
	public function set_search($search)
	{
		if (is_array($search)) {
			$this->search = $search;
			return true;
		}
		return false;
	}
	public function set_sort($sort)
	{
		if (is_array($sort)) {
			$this->sort = $sort;
			return true;
		}
		return false;
	}

	public function set_ajax_src($val)
	{
		$this->ajax_src = $val;
	}
	public function set_empty_text($val)
	{
		if ($val) $this->empty_text = $val;
	}
	public function set_striped($val)
	{
		$this->striped = $val;
	}
	public function set_bordered($val)
	{
		$this->bordered = $val;
	}
	public function set_hover($val)
	{
		$this->hover = $val;
	}
	function set_date_columns($val)
	{
		if (is_array($val)) $this->date_columns = $val;
	}
	function set_choice($val)
	{
		if (is_array($val)) $this->choice = $val;
	}
	function set_column_classes($val)
	{
		if (is_array($val)) $this->column_classes = $val;
	}
	function get_table_class()
	{
		return $this->table_class;
	}
	function set_table_class($val)
	{
		$this->table_class = $val;
		return $this->table_class;
	}
	function set_row_attributes($val)
	{
		if (is_array($val)) $this->row_attributes = $val;
	}
	public function print_output()
	{
		$table_id = random_string(10);
		$result = '';
		if (count($this->data) < 1 && !$this->ajax_src) return '<div class="alert alert-info table-info">' . $this->empty_text . '</div>';
		if (count($this->data) < 1 && $this->ajax_src) $result = '<div class="alert alert-info table-info">' . $this->empty_text . '</div>';
		$result .= '<div class="table-responsive' . (count($this->data) < 1 ? ' hidden' : '') . '"><table id="' . $table_id . '"' . ($this->ajax_src ? ' data-ajax-src="' . $this->ajax_src . '"' : '') . ' class="table' . ($this->bordered ? ' table-bordered' : '') . ($this->hover ? ' table-hover' : '') . ($this->striped ? ' table-striped' : '') . ' ' . $this->table_class . '">
                            <thead>';
		$result .= '<tr class="table-heading btn-default">';
		if (is_array($this->choice) && isset($this->choice['type']) && isset($this->choice['name'])) {
			$choice_type = $this->choice['type'] == 'radio' ? 'radio' : 'checkbox';
			$choice_val = isset($this->choice['value']) && $this->choice['value'] ? $this->choice['value'] : 'id';
			$result .= '<th>' . ($choice_type == 'radio' ? '#' : '<input type="checkbox" title="Select all" id="table_choice_' . $this->choice['name'] . '" />') . '</th>';
		}
		foreach ($this->columns as $key => $value) {
			$result .= '<th' . ($this->ajax_src ? ' data-key="' . $key . '"' : '') . ($this->ajax_src && isset($this->links[$key]) && is_array($this->links[$key]) ? ' data-link-url="' . $this->links[$key]['url'] . '" data-link-target="' . @$this->links[$key]['target'] . '"' : '') . ($this->ajax_src && is_array($this->search) && isset($this->search['fields']) && isset($this->search['q']) && is_array($this->search['fields']) && in_array($key, $this->search['fields']) ? ' data-searchable="1"' : '') . ($this->ajax_src && isset($this->editables[$key]) && is_array($this->editables[$key]) ? ' data-editable-object="' . $this->editables[$key]['object'] . '" data-editable-confirm="' . @$this->editables[$key]['confirm'] . '"' : '') . '>' . $value . (@in_array($key, $this->sort) ? $this->sort_link($key, $value) : '') . '</th>';
		}
		if (isset($this->options) && is_array($this->options) && count($this->options)) {
			$result .= "<th>Options</th>";
		}
		$result .= '</tr></thead><tbody>';
		if (count($this->data)) {
			foreach ($this->data as $data) {
				if (is_array($this->row_attributes)) {
					$attrs = Table::data_replace_data($this->row_attributes, $data);
					$attr_output = array();
					foreach ($attrs as $attr => $val) {
						$attr_output[] = $attr . '="' . $val . '"';
					}
					$attr_text = implode(' ', $attr_output);
				}
				$result .= "<tr" . (isset($attr_text) && $attr_text ? ' ' . $attr_text : '') . (isset($data['table_contextual_class']) ? ' class="' . $data['table_contextual_class'] . '"' : '') . ">";
				if (is_array($this->choice) && isset($this->choice['type']) && isset($this->choice['name'])) {
					$choice_type = $this->choice['type'] == 'radio' ? 'radio' : 'checkbox';
					$choice_val = isset($this->choice['value']) && $this->choice['value'] ? $this->choice['value'] : 'id';
					$result .= '<td>' . ($choice_type == 'radio' ? '<input type="radio" name="table_choices[' . $this->choice['name'] . ']" value="' . @$data[$choice_val] . '" />' : '<input type="checkbox" name="table_choices[' . $this->choice['name'] . '][' . @$data['id'] . ']" value="' . @$data[$choice_val] . '" />') . '</td>';
				}
				foreach ($this->columns as $key => $value) {
					$result .= '<td' . (is_array($this->column_classes) && isset($this->column_classes[$key]) ? ' class="' . $this->column_classes[$key] . '"' : '') . '>';
					if (isset($this->editables[$key]) && is_array($this->editables[$key])) {
						$result .= make_editable($data[$key], $this->editables[$key]['object'], $data['id'], $key, @$this->editables[$key]['confirm']);
					} elseif (isset($this->links[$key]) && is_array($this->links[$key])) {
						$url = $this->links[$key]['url'];
						foreach ($data as $col => $va) {
							if (!is_array(@$data[$col])) $url = str_replace('{' . $col . '}', urlencode(@$data[$col]), $url);
						}
						$result .= '<a href="' . $url . '"' . (isset($this->links[$key]['target']) ? ' target="' . $this->links[$key]['target'] . '"' : '') . (isset($this->links[$key]['title']) ? ' title="' . $this->links[$key]['title'] . '"' : '') . '>' . $this->search_replace($data[$key], $key) . '</a>';
					} elseif (is_array($this->date_columns) && isset($this->date_columns[$key])) {
						$result .= is_numeric($data[$key]) ? ($data[$key] ? date($this->date_columns[$key] == 'default' ? $this->date_default : $this->date_columns[$key], $data[$key]) : '-') : $data[$key];
					} else {
						$result .= $this->search_replace($data[$key], $key);
					}
					$result .= "</td>";
				}
				if (isset($this->options) && is_array($this->options) && count($this->options)) {
					$result .= "<td>";
					for ($j = 0; $j < count($this->options); $j++) {
						if (isset($this->options[$j]['depends_key']) && isset($this->options[$j]['depends_value']) && (@$this->options[$j]['depends_inverse'] ? @$data[$this->options[$j]['depends_key']] == $this->options[$j]['depends_value'] : @$data[$this->options[$j]['depends_key']] != $this->options[$j]['depends_value'])) continue;
						$url = $this->options[$j]['url'];
						foreach ($data as $col => $va) {
							if (!is_array(@$data[$col])) $url = str_replace('{' . $col . '}', (strpos($url, '?') && strpos($url, '?') < strpos($url, '{' . $col . '}') ? urlencode($data[$col]) : $data[$col]), $url);
						}
						$result .= make_button($this->options[$j]['text'], $url, @$this->options[$j]['type'], $this->options[$j]['icon'], @$this->options[$j]['target'], Table::data_replace_data(@$this->options[$j]['attrs'], $data));
						$result .= ' ';
					}
					$result .= "</td>";
				}
				$result .= "</tr>";
			}
		}
		$result .= "</tbody></table></div>";
		if (is_array($this->choice) && isset($this->choice['type']) && isset($this->choice['name'])) {
			$choice_type = $this->choice['type'] == 'radio' ? 'radio' : 'checkbox';
			$choice_val = isset($this->choice['value']) && $this->choice['value'] ? $this->choice['value'] : 'id';
			if ($choice_type == 'checkbox') {
				$result .= '
					<script type="text/javascript">
					  $(document).ready(function(e){
						  $(\'#table_choice_' . $this->choice['name'] . '\').click(function(d){ 
							  $(\'#' . $table_id . ' tbody :checkbox\').click();
						  });
					  });
					</script>
				';
			}
		}
		return $result;
	}
	private function search_replace($text, $field)
	{
		return is_array($this->search) && isset($this->search['fields']) && isset($this->search['q']) && $this->search['q'] && is_array($this->search['fields']) && in_array($field, $this->search['fields']) ? preg_replace('/(' . $this->search['q'] . ')/i', '<mark>$1</mark>', $text) : $text;
	}
	public static function data_replace($text, $data)
	{
		$result = $text;
		if (is_array($data)) {
			foreach ($data as $col => $val) {
				if (!is_array($val)) $result = str_replace('{' . $col . '}', $val, $result);
			}
		}
		return $result;
	}
	public static function data_replace_data($array, $data)
	{
		$result = $array;
		if (is_array($data) && is_array($array)) {
			foreach ($result as $col => $val) {
				$result[$col] = Table::data_replace($val, $data);
			}
		}
		return $result;
	}
	private function sort_link($key, $text)
	{
		global $APP;
		$asc = $APP->get('sort_key') == $key && $APP->get('sort_asc') == 1 ? 0 : 1;
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$sort_preg = '/sort\=[a-z0-9_]+/';
		$asc_preg = '/asc\=[0-9]+/';
		$sort_query = 'sort=' . $key;
		$asc_query = 'asc=' . $asc;
		if (strpos($url, 'sort=')) {
			$url = preg_replace($sort_preg, $sort_query, $url);
		} else {
			if (strpos($url, '?') === false) {
				$url .= '?' . $sort_query;
			} else {
				$url .= '&' . $sort_query;
			}
		}
		if (strpos($url, 'asc=')) {
			$url = preg_replace($asc_preg, $asc_query, $url);
		} else {
			if (strpos($url, '?') === false) {
				$url .= '?' . $asc_query;
			} else {
				$url .= '&' . $asc_query;
			}
		}
		return '<a' . ($this->ajax_src ? ' data-ajax-type="json"' : '') . ' href="' . $url . '" data-toggle="tooltip" class="sort-icon " title="Sort by ' . $text . '"><i class="fa fa-sort-' . ($asc ? 'up' : 'down') . '"></i></a>';
	}
	public static function load_combo_from_table($table, $pattern, $default_id = '', $value_column = 'id')
	{
		$table = DB_PREFIX . $table;
		return self::load_combo_from_query("SELECT * FROM $table", $pattern, $default_id, $value_column);
	}
	public static function load_combo_from_query($query, $pattern, $default_id = '', $value_column = 'id')
	{
		$db = new DB();
		$data = $db->get_query_set($query);
		return self::load_combo_from_data($data, $pattern, $default_id, $value_column);
	}
	public static function load_combo_from_data($data, $pattern, $default_id = '', $value_column = 'id')
	{
		$result = '';
		if (!is_array($data)) return;
		foreach ($data as $d) {
			$text = $pattern;
			foreach ($d as $key => $val) {
				$text = str_replace('{' . $key . '}', $val, $text);
			}
			$result .= '<option value="' . $d[$value_column] . '"' . ($default_id == $d[$value_column] ? ' selected="selected"' : '') . '>' . $text . '</option>';
		}
		return $result;
	}
	public static function load_value_from_table($table, $where, $column, $concat_column = '')
	{
		$db = new DB();
		$where = intval($where);
		$data = $db->get_query_set("SELECT * FROM $table WHERE id = $where", true);
		if (isset($concat_column) && $concat_column) {
			$column2 = isset($data[$concat_column]) ? $data[$concat_column] : $concat_column;
			$concat = ' - ' . $column2;
		} else $concat = '';
		return $data[$column] . $concat;
	}
	public static function get_enum_list($table, $field)
	{
		$db = new DB();
		$enum = $db->get_query_set("SHOW COLUMNS FROM " . DB_PREFIX . "$table WHERE Field =  UNHEX('" . bin2hex($field) . "')", true);
		if ($enum) {
			preg_match("/^enum\(\'(.*)\'\)$/", $enum['Type'], $matches);
			$list = explode("','", $matches[1]);
			return $list;
		}
		return [];
	}
	public static function load_combo_from_enum_list($table, $field, $default_value = '')
	{
		$result = '';
		$data = self::get_enum_list($table, $field);
		if (!$data) return;
		foreach ($data as $d) {
			$result .= '<option value="' . $d . '"' . ($default_value == $d ? ' selected="selected"' : '') . '>' . $d . '</option>';
		}
		return $result;
	}

	public function save_excel_data($filename)
	{
		/** Include PHPExcel */
		require_once GLOBAL_PATH . 'plugins/PHPExcel-develop/Classes/PHPExcel.php';
		require_once GLOBAL_PATH . 'plugins/PHPExcel-develop/Classes/PHPExcel/Cell.php';


		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings = array(' memoryCacheSize ' => '8MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties PHPExcel_Cell::stringFromColumnIndex($i)
		$objPHPExcel->getProperties()->setCreator("MyCyber Media Solutions")
			->setLastModifiedBy("MyCyber Media Solutions")
			->setTitle("Excel generated data")
			->setSubject("Form records")
			->setDescription("MyCyber document for PHPExcel, generated using PHP classes.")
			->setKeywords("office PHPExcel php")
			->setCategory("Datasheet");


		// Add some data
		$sheet = $objPHPExcel->setActiveSheetIndex(0);
		$col = $this->columns;
		$rows = $this->data;

		$i = 0;
		foreach ($col as $c) { //write headings
			$sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i) . '1', $c);
			$i++;
		}
		$i = 2; //nxt row
		foreach ($rows as $row) {
			$j = 0; //start collumn
			foreach ($this->columns as $key => $value) {
				$sheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex($j) . $i, $row[$key]);
				$j++;
			}
			$i++;
		}
		//Size the cells
		$objPHPExcel->getActiveSheet()->calculateColumnWidths();
		$j = 0;
		foreach ($this->columns as $key) {
			$objPHPExcel->getActiveSheet()->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($j))
				->setAutoSize(true);
			$j++;
		}
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		return $objWriter->save($filename . '.xls');
	}
}
