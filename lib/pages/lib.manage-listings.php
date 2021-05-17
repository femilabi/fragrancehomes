<?php
$APP->setTitle("Manage Listings");
//require_login('admin');
//publish or draft listing
if (isset($_GET['listing_id']) && $_GET['listing_id']) {
    $listing = new DBRow('listings', 'id', intval($_GET['listing_id']));
    if ($listing->exists()) {
        if (isset($_GET['action']) && $_GET['action']) {
            $listing->set('status', ($_GET['action'] == 'publish' ? "available" : ($_GET['action'] == 'unpublish' ? "unavailable" : "sold_out" )));
            $listing->save();
            $APP->setSessionMsg('Property/Project (' . $listing->get('title') . ') status updated');
            if (isset($_GET['redirect']) && $_GET['redirect']) redirect($_GET['redirect']);
        }
    } else $APP->setSessionMsg('listing does not exists!', 'warning');
    if (isset($_GET['redirect']) && $_GET['redirect']) redirect($_GET['redirect']);
}

//Building query parameters
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sort = isset($_GET['sort']) && preg_match('/^[a-z0-9_]+$/', $_GET['sort']) ? $_GET['sort'] : 'created_date'; //optional replace default here
$APP->assign('sort_key', $sort);
$direction = isset($_GET['asc']) ? ($_GET['asc'] == 1 ? 'ASC' : 'DESC') : 'DESC'; //replace default here
$APP->assign('sort_asc', ($direction == 'ASC' ? 1 : 0));
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = $APP->getSettings('list_per_page') ? : 50;
$clause = '';
if (!(isset($_GET['action']) && $_GET['action'] == 'search')) $clause = build_filter($filter);

//SEARCH
$search = '';
$search_fields = array('title', 'features');
if (isset($_GET['q']) && $_GET['q']) {
    $q = $_GET['q'];
    $search = build_search($q, $search_fields);
}

//SQL
$where =  " WHERE (1=1)" . ($clause ? " AND (" . $clause . ")" : "") . ($search ? " AND (" . $search . ")" : "");
$sql = "SELECT * FROM " .
    DB_PREFIX . "listings"
    . $where
    . ($sort ? " ORDER BY " . $sort . " " . $direction : "")
    . " LIMIT " . ($page - 1) * $per_page . ", " . $per_page;
$pager_sql = "SELECT COUNT(id) as total
			FROM " . DB_PREFIX . "listings"
    . $where;
//Query
$total = $DB->get_query_set($pager_sql, 1);
$total = @$total['total'];
$data = $DB->get_query_set($sql);

$badge_classes = [
    "available" => "success",
    "unavailable" => "danger",
    "sold_out" => "warning",
];

if (is_array($data)) {
    for ($i = 0; $i < count($data); $i++) {
        $data[$i]["display_price"] = price_format($data[$i]["price"], true);
        $data[$i]['display_image'] = '<img src="/' . @$data[$i]['image'] . '" width="100px">';
        $data[$i]['display_status'] = '<span class="badge badge-' . $badge_classes[$data[$i]['status']] . '">' . $data[$i]['status'] . '</span>';
    }
}
$APP->assign('listings_data', $data);
$APP->assign('pager_current_page', $page);
$APP->assign('pager_total_pages', @ceil($total / $per_page));