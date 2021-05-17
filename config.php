<?php
//Paths
define('APP_PROTOCOL', 'https://');
define('HOST', $_SERVER['HTTP_HOST']);
define('HOME_DIR', APP_PROTOCOL . HOST . '/'); //App home page URL
define('BASE_DIR', HOME_DIR); //Parent App URL if any
define('HTML_BASE', HOME_DIR); //HTML Base be provided in base tag
define('CDN_DIR', APP_PROTOCOL . 'cdn.mycyber.org/'); //HTML Base be provided in base tag

define('SELF_PATH', dirname(__DIR__) . '/'); // Path to App roots dir (same as SYS_PATH if app not a child
define('SYS_PATH', SELF_PATH); //Path to Parent App Dir if any (if no parent, App's root dir
define('GLOBAL_PATH', SYS_PATH . 'global/'); // Path to Network gloabal Dir

define('INCLUDE_PATH', SELF_PATH . 'includes/');
define('CLASS_PATH', GLOBAL_PATH . 'classes/');
define('LIB_PATH', SELF_PATH . 'lib/');
define('TEMPLATE_PATH', SELF_PATH . 'templates/');
define('WEBAPP_CONFIG', INCLUDE_PATH . 'app/app_config.php');

//Database
define('DB_PREFIX', 'ztp_');
define('DB_NAME', 'admin_ztp');
define('DB_USER', 'admin_ztp');
define('DB_PASS', 'EmWvp2gM9X');
define('DB_HOST', 'localhost');

//Session
define('USER_SESSION_HOLDER', 'app_user');

//App constants
define('DEFAULT_TITLE', 'Welcome');
define('TITLE_SUFFIX', ' | ZtpCloud Hosting');

//Email constants
define('ALLOW_EMAIL', 1);
define('ADMIN_NAME', 'ZtpCloud Platform');
define('ADMIN_EMAIL', 'no_reply@ztpcloud.com');
define('ADMIN_DOMAIN', 'ztpcloud.com');
define('SMTP_HOST', 'tescom.oy.gov.ng');
define('SMTP_PORT', 587);
define('SMTP_ENCRYPT', 'tls'); //'ssl', 'tls'
define('SMTP_USERNAME', 'no_reply@ztpcloud.com');
define('SMTP_PASSWORD', '2bhri7URSF');

//Payment Parameters
define('VOG_PAY_URL', 'https://voguepay.com/pay/');
define('VOG_MERCHANT_ID', '14292-23122');
define('PAY_NOTIFY_URL', HOME_DIR . 'vogue_process_pay/');
define('PAY_LANDING_URL', HOME_DIR . 'payment-details/');
define('VOG_DEVELOPER_CODE', 'DUMMY');

//reCaptcha Keys
define('RECAPTCHA_PUB_KEY', '...');
define('RECAPTCHA_PRIV_KEY', '...');

//reseller api Keys
define('RESELLER_CLUB_RESELLER_ID', '1040061');
define('RESELLER_CLUB_API_KEY', 'gVPo6FgBuchoD5UPWfaGQJv5RMPxEvvl');
define('RESELLER_CLUB_API_DIR', 'https://test.httpapi.com/api/');

//PayPal
define("PAYPAL_API_KEY", "AY9eHWa4_CBNYlUMfj1EGjBrr1xk_JGzYeg8-DdZfAyzimS0z8A4cMQnvkC3NIYbSZWLApzMwXHKlJg6");
define("PAYPAL_API_SECRET", "EEyPlh-MPAyATCZW4KOwxcQqfBhBpG38o-XCawJjmeLgwUuppSArcY4QW8y-_IXVj18a9bcUwBv1-TAu");
define("PAYPAL_RETURN_URI", HOME_DIR . "paypal-hook/");
define("PAYPAL_CURRENCY", "USD");
define("PAYPAL_CURRENCY_ID", 252);
define("PAYPAL_MODE", "sandbox");
define("PAYPAL_ALLOW", 1);

// XTNL Configurations
define("TUNNEL_URL", "https://fs.ztpcloud.com/");
define("TUNNEL_SECRETE", "ghdfgiw4r7r89roastdgsd;pafpds8;df8pf't'r[pky'ty[rtjPPDE9UWEWE8Y'ERWRTK\RTRKT");
