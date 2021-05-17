<?php
class Counter{
	private $cookie_name = 'mycybercounter_882';
	private $db;
	private $table = 'mycyber_visitors_counter';
	function __construct($auto_count = false){
		$this->db = new DB();
		
		if($auto_count) $this->count();
	}
	function count(){
		//if(!isset($_COOKIE[$this->cookie_name])){
			$user = (isset($_SESSION[USER_SESSION_HOLDER]['username']) && isset($_SESSION[USER_SESSION_HOLDER]['role']))? $_SESSION[USER_SESSION_HOLDER]['role'] . '-' .$_SESSION[USER_SESSION_HOLDER]['username']: 'guest';
			$this->add_entry($user);
		//}
		//setcookie($this->cookie_name, 'checked', time()+ (60*24));
	}
	
	private function add_entry($user){
		$entry = new DBRow($this->table, 'id');
		$entry->set('timestamp', time());
		$entry->set('ip', $_SERVER['REMOTE_ADDR']);
		$entry->set('user', $user);
		return $entry->save();
	}
	function get_count($when = 'today'){
		$start = 0;
		$end = 0;
		switch($when){
			case 'today':
				$start = mktime(0, 0, 0, date('n'), date('d'), date('Y'));
				$end=time();
				break;
			case 'yesterday':
				$start = mktime(0, 0, 0, date('n'), date('d') - 1, date('Y'));
				$end= mktime(23, 59, 59, date('n'), date('d') - 1, date('Y'));
				break;
			case 'this_week':
				$mon = strtotime('this week');
				$start = mktime(0,0,0, date('n', $mon), date('d', $mon) - 1, date('Y')); // sunda morning
				$end= time();
				break;
			case 'last_week':
				$mon = strtotime('last week');
				$start = mktime(0,0,0, date('n', $mon), date('d', $mon) - 1, date('Y')); // sunda morning
				$end= strtotime('+7 days',$start) - 1;
				break;
			case 'this_month':
				$start = mktime(0, 0, 0, date('n'), 1, date('Y'));
				$end=time();
				break;
			case 'last_month':
				$start = mktime(0, 0, 0, date('n') - 1, 1, date('Y'));
				$end= mktime(23, 59, 59, date('n')-1, date('t', $start), date('Y'));
				break;
			case 'this_year':
				$start = mktime(0, 0, 0, 1, 1, date('Y'));
				$end=time();
				break;
			case 'last_year':
				$start = mktime(0, 0, 0, 1, 1, date('Y') - 1);
				$end= mktime(23, 59, 59, 12, 31, date('Y') - 1);
				break;
			default: //today
				$start = mktime(0, 0, 0, date('n'), date('d'), date('Y'));
				$end=time();
				break;
		}
		$query = "SELECT COUNT(id) AS total FROM ".DB_PREFIX.$this->table." WHERE timestamp >= '".$start."' AND timestamp <= '".$end."'";
		$entries = $this->db->get_query_set($query, true);
		return $entries['total'];
	}
}
?>