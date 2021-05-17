<?php require_login('admin');
$lib = @$_GET['ap']? $_GET['ap'] : 'dashboard';
switch($lib){
	case 'dashboard':
		
	break;
}
$APP->setTemplate('admin');
$APP->setTitle('Admin CRM');
$APP->assign('active_menu', 'admin');
$APP->assign('active_submenu', 'dashboard');

$script = LIB_PATH.'admin/admin.'.$lib.'.php';
if(is_file($script)){
	$APP->setPage('admin/'.$lib);
	require_once($script);
}
?>