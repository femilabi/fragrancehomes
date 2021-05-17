<?php
class App
{
	public $version = '1.2';

	private $json = array();
	private $isJSON;
	private $isAJAX;

	private $page;
	private $template;
	private $title;

	private $settings = array();
	private $settingsLoaded;

	private $error;
	private $msg = array();
	private $session_msg = array();

	private $requireLogin;

	private $executed = false;
	private $lib;

	private $styles = array();
	private $metas = array();
	private $scripts = array();
	private $html = array();

	private $params = array();

	function __construct($loadF_from_query = true, $self_rewrite = false)
	{
		$this->json = array();
		$this->isJSON = false;
		if ($self_rewrite) $this->parse_rewrite();
		else
		if ($loadF_from_query) $this->loadQueryData();
		if (isset($_SESSION['app_session_msg']['msg']) && isset($_SESSION['app_session_msg']['type'])) {
			$this->setMsg($_SESSION['app_session_msg']['msg'], $_SESSION['app_session_msg']['type']);
			unset($_SESSION['app_session_msg']['msg']);
			unset($_SESSION['app_session_msg']['type']);
		}
	}
	private function parse_rewrite()
	{
		$path = trim(parse_url(APP_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

		$params = explode('/', $path);
		if (!(is_array($params) && count($params) && $params[0])) {
			$params = ['index'];
		}
		$this->params = $params;
		$this->setLib($params[0]);
	}
	function p($index)
	{
		if (isset($this->params[$index])) return ($this->params[$index]);
		return '';
	}
	function getParams()
	{
		return $this->params;
	}
	function setParams($params)
	{
		$this->params = $params;
	}
	function loadQueryData()
	{
		$page = isset($_GET['p']) && $_GET['p'] ? $_GET['p'] : 'index';
		$this->setLib($page);
		if (file_exists(WEBAPP_CONFIG)) include_once(WEBAPP_CONFIG);
		$this->setUpApp();
	}
	function debug()
	{
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
	}
	function assign($key, $value)
	{
		if (isset($key) && isset($value)) $this->json[$key] = $value;
	}
	/*
		To append values to an array
		creates the array is not exist
		Warning: Ignores if the param is already set and is not an array
	*/
	function assign_append($key, $value)
	{
		if (isset($key) && isset($value)) {
			if (!isset($this->json[$key])) $this->json[$key] = [];
			if (!is_array($this->json[$key])) return;
			$this->json[$key][] = $value;
		}
	}
	function assignData($value)
	{
		$this->json = $value;
	}
	function get($key)
	{
		return isset($this->json[$key]) ? $this->json[$key] : '';
	}
	function unsetData($key)
	{
		unset($this->json[$key]);
	}
	function setTemplate($template)
	{
		if (isset($template)) $this->template = $template;
	}
	function getTemplate()
	{
		return $this->template;
	}
	private function getTemplateFile()
	{
		if (isset($this->template) && $this->template) {
			if (file_exists(TEMPLATE_PATH . $this->getTemplate() . '.php')) return TEMPLATE_PATH . $this->getTemplate() . '.php';
			if (file_exists(TEMPLATE_PATH . $this->getTemplate() . '/index.php')) return TEMPLATE_PATH . $this->getTemplate() . '/index.php';
			if (file_exists(TEMPLATE_PATH . 'index/' . $this->getTemplate() . '.php')) return TEMPLATE_PATH . 'index/' . $this->getTemplate() . '.php';
			if (file_exists(TEMPLATE_PATH . 'index.php')) return TEMPLATE_PATH . 'index.php';
			if (file_exists(TEMPLATE_PATH . 'index/index.php')) return TEMPLATE_PATH . 'index/index.php';
		} else {
			if (file_exists(TEMPLATE_PATH . 'index.php')) return TEMPLATE_PATH . 'index.php';
			if (file_exists(TEMPLATE_PATH . 'index/index.php')) return TEMPLATE_PATH . 'index/index.php';
		}
		return;
	}
	function getTemplatePath()
	{
		$dirname = dirname($this->getTemplateFile());
		return $dirname == '/' ? $dirname : $dirname . '/';
	}

	function getTemplateDir()
	{
		return str_replace(TEMPLATE_PATH, HOME_DIR . 'templates/', $this->getTemplatePath());
	}

	function setIsJSON($boolean)
	{
		if (isset($boolean)) $this->isJSON = $boolean;
	}
	function isJSON()
	{
		return $this->isJSON;
	}
	function getJSON()
	{
		return json_encode($this->json);
	}
	function getData()
	{
		return $this->json;
	}

	function setIsAJAX($boolean)
	{
		if (isset($boolean)) $this->isAJAX = $boolean;
	}
	function isAJAX()
	{
		return $this->isAJAX;
	}

	function setRequireLogin($boolean)
	{
		if (isset($boolean)) $this->requireLogin = $boolean;
	}
	function requiresLogin()
	{
		return $this->requireLogin;
	}

	function setTitle($title)
	{
		if (isset($title)) $this->title = $title;
	}
	function getTitle()
	{
		return ((isset($this->title) && $this->title) ? $this->title : DEFAULT_TITLE);
	}
	function getFullTitle()
	{
		return $this->getTitle() . TITLE_SUFFIX;
	}

	function setPage($page)
	{
		if (isset($page)) $this->page = $page;
	}
	function getPage()
	{
		return $this->page;
	}
	function setLib($lib)
	{
		if (isset($lib)) $this->lib = $lib;
		$this->setPage($lib);
	}
	function getLib()
	{
		return $this->lib;
	}
	function setError($key, $msg)
	{
		$this->error[$key] = $msg;
	}
	function getError($key)
	{
		return isset($this->error[$key]) ? $this->error[$key] : '';
	}
	function getAllError()
	{
		return $this->error;
	}
	function setMsg($msg, $type = "info")
	{
		$this->msg['type'] = $type;
		$this->msg['msg'] = $msg;
	}
	function setSessionMsg($msg, $type = "info")
	{
		$_SESSION['app_session_msg']['type'] = $type;
		$_SESSION['app_session_msg']['msg'] = $msg;
	}
	function getMsg()
	{
		return $this->msg;
	}
	function eraseMsg()
	{
		unset($this->msg);
		unset($this->session_msg);
	}
	private function loadSettings()
	{
		global $DB;
		if (!is_object($DB)) return;
		$data = $DB->get_query_set("SELECT * FROM " . DB_PREFIX . "app_settings");
		foreach ($data as $d) {
			$this->settings[$d['setting_key']] = $d['setting_value'];
		}
		$this->settingsLoaded = true;
	}
	function getSettings($key)
	{
		if (!$this->settingsLoaded) $this->loadSettings();
		return @$this->settings[$key];
	}
	function saveSettings($key, $value)
	{
		$setting = new DBRow('app_settings', 'id');
		$setting->set('setting_key', $key);
		$setting->search('setting_key');
		$setting->set('setting_value', $value);
		if ($setting->save()) {
			$this->loadSettings();
			return true;
		}
	}
	function getContent($key)
	{
		global $APP;
		$content = new DBRow('app_contents', 'id');
		$content->set('content_key', $key);
		$content->search('content_key');
		$c = $content->get('content');
		if (substr($c, 0, 6) == 'file::') {
			$file = substr($c, 6);
			if (file_exists(INCLUDE_PATH . 'contents/' . $file)) {
				ob_start();
				include INCLUDE_PATH . 'contents/' . $file;
				$result = ob_get_contents();
				ob_end_clean();
				//$result = file_get_contents(INCLUDE_PATH.'contents/'.$file);
				return $result;
			} else return '';
		} else return $c;
	}
	function saveContent($key, $value)
	{
		$content = new DBRow('app_contents', 'id');
		$content->set('content_key', $key);
		$content->search('content_key');
		$content->set('content', $value);
		return $content->save();
	}
	function hasContent($key)
	{
		global $APP;
		$content = new DBRow('app_contents', 'id');
		$content->search('content_key', $key);
		if ($content->exists()) return true;
		else return false;
	}
	function printMsg()
	{
		if (!(is_array($this->msg) && isset($this->msg['type']) && $this->msg['type'] && isset($this->msg['msg']) && $this->msg['msg'])) return;
		$alert_class = '';
		switch ($this->msg['type']) {
			case 'success':
				$alert_class = 'success';
				break;
			case 'error':
				$alert_class = 'danger';
				break;
			case 'warning':
				$alert_class = 'warning';
				break;
			default:
				$alert_class = 'info';
		}
		echo ('<div style="display: block;" id="" class="alert alert-block alert-' . $alert_class . ' fade in show">
                    	<button type="button" class="close" data-dismiss="alert">×</button>
                    	<p><strong><i class="icon-info-sign"></i> ' . $this->msg['msg'] . '</strong></p>
                    </div>');
	}
	function printErrors($title = 'Errors encountered')
	{
		if (!(is_array($this->error) && count($this->error) > 0)) return;
		$result = '<div style="display: block;" id="" class="alert alert-block alert-danger">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<p><strong><i class="icon-info-sign"></i>' . $title . '</strong></p>
			<ul>';
		foreach ($this->error as $err) {
			$result .= '<li>' . $err . '</li>';
		}
		$result .= '</ul>
		</div>';
		echo ($result);
	}
	private function app_include()
	{
		global $APP, $DB, $USER, $COUNTER;
		$path = LIB_PATH . 'pages/lib.' . $this->getLib() . '.php';
		if (file_exists($path)) include_once($path);
	}
	function execute()
	{
		$this->executed = true;
		$this->app_include();
	}
	function isLoggedIn()
	{
		global $USER;
		if (isset($USER) && is_object($USER)) return $USER->is_loggedin();
		return;
	}
	function setUpApp()
	{
	}
	function printOutput($finishRequest = false)
	{
		global $APP, $DB, $USER, $COUNTER;
		if (!$this->executed) die('Access denied!');
		if ($finishRequest && false) {
			ignore_user_abort(true);
			set_time_limit(0);
			ob_start();
			$this->doPrint();
			//echo str_pad($APP->getJSON(),4096)."\n";
			session_write_close();
			$size = ob_get_length();
			header("Connection: close");
			header("Content-Length: " . $size);
			ob_end_flush();
			ob_flush();
			flush();
		} else {
			$this->doPrint();
		}
	}
	function _exit()
	{
		global $DB;
		@$DB->close();
		exit;
	}
	private function doPrint()
	{
		global $APP, $DB, $USER, $COUNTER;
		if ($this->isJSON()) {
			$this->assign('status', $this->msg);
			$this->assign('errors', $this->error);
			@header('Content-Type: application/json');
			echo (json_encode($this->json));
			return;
		}
		if ($this->isAJAX()) {
			echo $this->content();
			return;
		}
		$template = $this->getTemplateFile();
		if ($template) {
			include_once($template);
		} else {
			echo $this->content();
		}
	}

	//Outputs app Page content
	function content()
	{
		global $APP, $DB, $USER, $COUNTER;
		$result = '';
		if (file_exists(SELF_PATH . 'pages/' . $APP->getPage() . '.php')) {
			ob_start();
			include SELF_PATH . 'pages/' . $APP->getPage() . '.php';
			$result .= ob_get_contents();
			ob_end_clean();
		} elseif (file_exists(SELF_PATH . 'pages/404.php')) {
			ob_start();
			include SELF_PATH . 'pages/404.php';
			$result .= ob_get_contents();
			ob_end_clean();
		} else {
			$result .= 'Page Not Found!';
		}
		//return '<div id="app_content">'.$result.'</div>';
		return $result;
	}

	// Output head
	function head()
	{
		global $APP;
		$result = '';
		if (file_exists(INCLUDE_PATH . 'app/head.php')) {
			ob_start();
			include INCLUDE_PATH . 'app/head.php';
			$result .= ob_get_contents();
			ob_end_clean();
		}
		$result .= $this->printMetas();
		$result .= $this->printStyles();
		return $result;
	}

	// Output scripts
	function scripts()
	{
		global $APP;
		$result = '';
		if (file_exists(INCLUDE_PATH . 'app/scripts.php')) {
			ob_start();
			include INCLUDE_PATH . 'app/scripts.php';
			$result .= ob_get_contents();
			ob_end_clean();
		}
		$result .= $this->printScripts();
		return $result;
	}

	// Output html attributes string
	function html()
	{
		$result = '';
		if (!is_array($this->html)) return $this->html;
		foreach ($this->html as $name => $value) {
			$result .= $name . ($value == '' ? ' ' : '="' . $value . '" ');
		}
		return $result;
	}

	//Styles
	function getStyles()
	{
		return $this->styles;
	}
	function addStyle($src, $media = '')
	{
		$arr = array();
		$arr['src'] = $src;
		if ($media) $arr['media'] = $media;
		for ($i = 0; $i < count($this->styles); $i++) {
			if ($this->styles[$i]['src'] == $src) {
				if ($media) $this->styles[$i]['media'] = $media;
				return;
			}
		}
		$this->styles[] = $arr;
	}

	//HTML Attributes
	function getHTMLAttributes()
	{
		return $this->html;
	}
	function getHTMLAttribute($name)
	{
		if (isset($this->html[$name])) return $this->html[$name];
		return '';
	}
	function setHTMLAttribute($name, $value)
	{
		$this->html[$name] = $value;
	}
	function hasHTMLAttribute($name)
	{
		return isset($this->html[$name]);
	}

	//Metas
	function getMetas()
	{
		return $this->metas;
	}
	function getMeta($name)
	{
		if (isset($this->metas[$name])) return $this->metas[$name];
		return '';
	}
	function setMeta($name, $value)
	{
		$this->metas[$name] = $value;
	}
	function hasMeta($name)
	{
		return isset($this->metas[$name]);
	}
	//Scripts
	function getScripts()
	{
		return $this->scripts;
	}
	function addScript($src, $content = '')
	{
		$arr = array();
		$arr['src'] = $src;
		if ($content) $arr['content'] = $content;
		for ($i = 0; $i < count($this->scripts); $i++) {
			if ($this->scripts[$i]['src'] == $src) {
				if ($content) $this->scripts[$i]['content'] = $content;
				return;
			}
		}
		$this->scripts[] = $arr;
	}

	//hidden... print metas
	private function printMetas()
	{
		$result = '';
		if (!is_array($this->metas)) return;
		foreach ($this->metas as $name => $value) {
			$result .= '<meta name="' . $name . '" content="' . $value . '">';
		}
		return $result;
	}

	//hidden... print tyles
	private function printStyles()
	{
		$result = '';
		if (!is_array($this->styles)) return;
		foreach ($this->styles as $style) {
			$result .= '<link rel="stylesheet" href="' . $style['src'] . '" type="text/css"' . (isset($style['media']) ? ' media="' . $style['media'] . '"' : '') . '>';
		}
		return $result;
	}

	//hidden... print scripts
	private function printScripts()
	{
		$result = '';
		if (!is_array($this->scripts)) return;
		foreach ($this->scripts as $script) {
			$result .= '<script type="text/javascript"' . ($script['src'] ? ' src="' . $script['src'] . '"' : '') . '>' . (isset($script['content']) ? $script['content'] : '') . '</script>';
		}
		return $result;
	}

	function route($route, $return)
	{
		if (!$route || $route == $this->getLib() || (is_array($route) && in_array($this->getLib(), $route))) {
			if (is_string($return)) $this->setLib($return);
			if (is_callable($return)) {
				$return = $return->bindTo($this);
				$this->setLib($return($this->getLib()));
			}
		}
	}
	function _get($route, $return)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'GET') return $this->route($route, $return);
	}
	function _post($route, $return)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') return $this->route($route, $return);
	}
	function _put($route, $return)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'PUT') return $this->route($route, $return);
	}
	function _delete($route, $return)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'DELETE') return $this->route($route, $return);
	}
}
