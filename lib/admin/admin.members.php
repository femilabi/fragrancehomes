<?php $APP->setTitle('Manage Users');
if(@$_GET['block_user'] && @$_GET['redirect']){
	$DB->query("UPDATE ".DB_PREFIX."users SET blocked = 1, blocked_memo = 'Your account has been flagged and blocked by an admin. Contact support for reconciliation' WHERE id = ".intval($_GET['block_user']));
	$APP->setSessionMsg('Query executed successfully');
	redirect(@$_GET['redirect']);
	return;
}
if(@$_GET['unblock_user'] && @$_GET['redirect']){
	$DB->query("UPDATE ".DB_PREFIX."users SET blocked = 0, active = 1 WHERE id = ".intval($_GET['unblock_user']));
	$APP->setSessionMsg('Query executed successfully');
	redirect(@$_GET['redirect']);
	return;
}
if(@$_GET['login_as_user'] && @$_GET['redirect']){
	if($USER->get('role') == 'admin' && $USER->get_meta('is_super_admin')){
		$user = new User(intval($_GET['login_as_user']));
		if($user->exists() && !$user->get_meta('is_super_admin')){
			$_SESSION[USER_SESSION_HOLDER] = $user->get_data();
			$APP->setSessionMsg('You are now using the platform as '.$user->get('fullname').'<br/>
			You may have to logout and login to your account back to continue admin access', 'success');
			redirect(HOME_DIR);
		}elseif($user->exists()){
			$APP->setSessionMsg('You do not have access to view this users dashboard', 'warning');
			redirect(@$_GET['redirect']);
		}else{
			$APP->setSessionMsg('User does not exist', 'warning');
			redirect(@$_GET['redirect']);
		}
	}else{
		$APP->setSessionMsg('You have not been granted that access', 'warning');
		redirect(@$_GET['redirect']);
	}
}
//Building query parameters
	$filter = isset($_GET['filter'])? $_GET['filter']: '';
	$sort = isset($_GET['sort']) && preg_match('/^[a-z0-9_]+$/', $_GET['sort'])? $_GET['sort']: 'joindate'; //optional replace default here
	$query_table = 'u';
	$APP->assign('sort_key', $sort);
	$direction = isset($_GET['asc'])? ($_GET['asc'] == 1? 'ASC' : 'DESC') : 'DESC'; //replace default here
	$APP->assign('sort_asc', ($direction == 'ASC'? 1: 0));
	$page= isset($_GET['page'])? intval($_GET['page']): 1;
	$per_page = $APP->getSettings('list_per_page');
	$clause = '';
	if(!(isset($_GET['action']) && $_GET['action'] == 'search')) $clause = build_filter($filter, $query_table);
	
	//SEARCH
	$search = '';
	$search_fields = array('u.id','u.email','u.fullname','u.btc_address');
	if(isset($_GET['q']) && $_GET['q']){
		$q = $_GET['q'];
		$search = build_search($q, $search_fields);
	}
	
	//SQL
	$where =  " WHERE (1 = 1)".($clause? " AND (".$clause.")": "").($search? " AND (".$search.")": "");
	$sql = "SELECT
				u.*, r.email AS referrer_email
			FROM 
				".DB_PREFIX."users AS u
			LEFT JOIN
				".DB_PREFIX."users AS r 
			ON 
				u.referrer_id = r.id"
			.$where
			." GROUP BY u.id"
			.($sort? " ORDER BY ".$sort." ".$direction : "")
			." LIMIT ".($page - 1) * $per_page.", ". $per_page
			; //var_dump($sql); exit;
	$pager_sql = "SELECT COUNT(u.id) as total
			FROM 
				".DB_PREFIX."users AS u"
			.$where;
	//Query
	$total = @$DB->get_query_set($pager_sql, 1);
	$total = @$total['total'];
	$data = @$DB->get_query_set($sql);
	
	if(is_array($data)){
		for($i = 0; $i < count($data); $i++){
			$data[$i]['status'] = $data[$i]['active']?($data[$i]['blocked']? 'Blocked' : 'Active'):'Not active';
			if($data[$i]['matched_10_count']) $data[$i]['table_contextual_class'] = 'danger';
		}
	}
	
    //total members
	$total_members_sql = "SELECT COUNT(id) as total
			FROM 
				".DB_PREFIX."users
			WHERE
				matched_10_count = 0
			";
	$total_members = @$DB->get_query_set($total_members_sql, 1);
	$total_members = @$total_members['total'];
	$APP->assign('total_members', $total_members);
	
    //currently active by status members
	$total_members_sql = "SELECT COUNT(id) as total
			FROM 
				".DB_PREFIX."users
			WHERE
				matched_10_count = 0
                AND active = 1
                AND blocked = 0
			";
	$total_members = @$DB->get_query_set($total_members_sql, 1);
	$total_members = @$total_members['total'];
	$APP->assign('active_members', $total_members);
	
    //REALLY active by total pd
	$total_members_sql = "SELECT COUNT(id) as total
			FROM 
				".DB_PREFIX."users
			WHERE
				matched_10_count = 0
                AND active = 1
                AND blocked = 0
                AND total_ph > 0
			";
	$total_members = @$DB->get_query_set($total_members_sql, 1);
	$total_members = @$total_members['total'];
	$APP->assign('really_active_members', $total_members);
	
    //blocked members
	$total_members_sql = "SELECT COUNT(id) as total
			FROM 
				".DB_PREFIX."users
			WHERE
				matched_10_count = 0
                AND blocked = 1
			";
	$total_members = @$DB->get_query_set($total_members_sql, 1);
	$total_members = @$total_members['total'];
	$APP->assign('blocked_members', $total_members);
	
    //assign values
	$APP->assign('users_data', $data);
	$APP->assign('pager_current_page', $page);
	$APP->assign('pager_total_pages', $total/$per_page);
	
	
	$APP->assign('active_menu', 'members');
	$APP->assign('active_submenu', 'gateway_messages');
	$APP->setPage('admin/members');
?>