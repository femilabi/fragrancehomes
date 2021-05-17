<?php
require_once(dirname(__FILE__) . '/lib/config.php');

require_once(GLOBAL_PATH . 'lib/fns.php');
require_once(GLOBAL_PATH . 'lib/content.php');
require_once(GLOBAL_PATH . 'lib/verify.php');
require_once(GLOBAL_PATH . 'lib/social.php');

require_once(LIB_PATH . 'fns.php');
// require_once(LIB_PATH . 'main.fns.php');
// require_once(LIB_PATH . 'ip.fns.php');
// require_once(LIB_PATH . 'resellerclub.fns.php');
// require_once(LIB_PATH . 'paypal.fns.php');

require_once(CLASS_PATH . 'app.php');
require_once(CLASS_PATH . 'db.php');
require_once(CLASS_PATH . 'dbrow.php');
require_once(CLASS_PATH . 'table.php');
require_once(CLASS_PATH . 'user.php');
//require_once(CLASS_PATH.'counter.php');


$APP = new App(false, true);
$DB = new DB();
//$COUNTER = new Counter(true);
