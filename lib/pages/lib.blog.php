<?php $APP->setTemplate('blog');
$APP->setPage('articles');
$APP->setTitle('Updates');
if (@$_GET['category']) {
	$cat = new DBRow('post_categories', 'id');
	$cat->search('slug', $_GET['category']);
	if ($cat->exists()) {
		$APP->assign('category_data', $cat->get_data());
		//$APP->assign('return_url', @$_GET['return_url']);
		$APP->setTitle($cat->get('title') . ' | System Updates');
		$_GET['filter']['category_id'] = $cat->get_id();
	} else $APP->setMsg('No updates under this category yet or category does not exist<br/>Displaying default updates', 'error');
}
//Building query parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sort = isset($_GET['sort']) && preg_match('/^[a-z0-9_]+$/', $_GET['sort']) ? $_GET['sort'] : 'created_date'; //optional replace default here
$APP->assign('sort_key', $sort);
$direction = isset($_GET['asc']) ? ($_GET['asc'] == 1 ? 'ASC' : 'DESC') : 'DESC'; //replace default here
$APP->assign('sort_asc', ($direction == 'ASC' ? 1 : 0));
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 12;
$clause = '';
if (!(isset($_GET['action']) && $_GET['action'] == 'search')) $clause = build_filter($filter);

//SEARCH
$search = '';
$search_fields = array('');
$APP->assign('table_search_fields', $search_fields); //inform table
if (isset($_GET['q']) && $_GET['q']) {
	$q = $_GET['q'];
	$search = build_search($q, $search_fields);
}

//SQL
$where =  " WHERE (published = 1 AND p.category_id = c.id)" . ($clause ? " AND (" . $clause . ")" : "") . ($search ? " AND (" . $search . ")" : "");
$sql = "SELECT p.*, c.title AS category_title, c.slug AS category_slug FROM " .
	DB_PREFIX . "posts AS p, " . DB_PREFIX . "post_categories AS c"
	. $where
	. ($sort ? " ORDER BY " . $sort . " " . $direction : "")
	. " LIMIT " . ($page - 1) * $per_page . ", " . $per_page;
$pager_sql = "SELECT COUNT(p.id) as total
			FROM " . DB_PREFIX . "posts AS p, " . DB_PREFIX . "post_categories AS c"
	. $where;
//Query
$total = $DB->get_query_set($pager_sql, 1);
$total = @$total['total'];
$data = $DB->get_query_set($sql);

if (is_array($data)) {
	for ($i = 0; $i < count($data); $i++) {
		//if(!$data[$i]['successful'] && !$data[$i]['pending']) $data[$i]['table_contextual_class'] = 'danger';
		//if(!$data[$i]['successful'] && $data[$i]['pending']) $data[$i]['table_contextual_class'] = 'warning';
	}
}
$APP->assign('posts_data', $data);
$APP->assign('pager_current_page', $page);
$APP->assign('pager_total_pages', $total / $per_page);
