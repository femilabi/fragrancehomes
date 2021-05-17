<?php require_login('admin');
	//publish or draft post
	if(isset($_GET['post_id']) && $_GET['post_id']){
		$post = new DBRow('posts', 'id', intval($_GET['post_id']));
		if($post->exists()){
			if(isset($_GET['action']) && $_GET['action']){
				$post->set('published', $_GET['action'] == 'publish'? 1 : 0);
				$post->save();
				$APP->setSessionMsg('Post ('.$post->get('title').') status updated');
				if(isset($_GET['redirect']) && $_GET['redirect']) redirect($_GET['redirect']);
			}
		}else $APP->setSessionMsg('Post does not exists!', 'warning');
		if(isset($_GET['redirect']) && $_GET['redirect']) redirect($_GET['redirect']);
	}
	
//Building query parameters
	$filter = isset($_GET['filter'])? $_GET['filter']: '';
	$sort = isset($_GET['sort']) && preg_match('/^[a-z0-9_]+$/', $_GET['sort'])? $_GET['sort']: 'created_date'; //optional replace default here
	$APP->assign('sort_key', $sort);
	$direction = isset($_GET['asc'])? ($_GET['asc'] == 1? 'ASC' : 'DESC') : 'DESC'; //replace default here
	$APP->assign('sort_asc', ($direction == 'ASC'? 1: 0));
	$page= isset($_GET['page'])? intval($_GET['page']): 1;
	$per_page = $APP->getSettings('list_per_page');
	$clause = '';
	if(!(isset($_GET['action']) && $_GET['action'] == 'search')) $clause = build_filter($filter);
	
	//SEARCH
	$search = '';
	$search_fields = array('p.title');
	if(isset($_GET['q']) && $_GET['q']){
		$q = $_GET['q'];
		$search = build_search($q, $search_fields);
	}
	
	//SQL
	$where =  " WHERE (p.category_id = c.id)".($clause? " AND (".$clause.")": "").($search? " AND (".$search.")": "");
	$sql = "SELECT p.*, c.title AS category_title FROM ".
			DB_PREFIX."posts AS p,".
			DB_PREFIX."post_categories AS c"
			.$where
			.($sort? " ORDER BY ".$sort." ".$direction : "")
			." LIMIT ".($page - 1) * $per_page.", ". $per_page;
	$pager_sql = "SELECT COUNT(p.id) as total
			FROM ".DB_PREFIX."posts AS p,".
			DB_PREFIX."post_categories AS c"
			.$where;
	//Query
	$total = @$DB->get_query_set($pager_sql, 1);
	$total = @$total['total'];
	$data = @$DB->get_query_set($sql);
	
	if(is_array($data)){
		for($i = 0; $i < count($data); $i++){
			$data[$i]['status'] = $data[$i]['published']? 'Published' : 'Draft';
			$data[$i]['table_contextual_class'] = $data[$i]['published']? '' : 'warning';
		}
	}
	$APP->assign('posts_data', $data);
	$APP->assign('pager_current_page', $page);
	$APP->assign('pager_total_pages', $total/$per_page);
?>