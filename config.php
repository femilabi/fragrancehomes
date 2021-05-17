<?php
//Paths
define("APP_PROTOCOL", "http://");
define("HOST", $_SERVER["HTTP_HOST"]);
define("HOME_DIR", APP_PROTOCOL . HOST . "/"); //App home page URL
define("BASE_DIR", HOME_DIR); //Parent App URL if any
define("HTML_BASE", HOME_DIR); //HTML Base be provided in base tag
define("CDN_DIR", BASE_DIR . "cdn/"); //HTML Base be provided in base tag

define("SELF_PATH", dirname(__DIR__) . "/"); // Path to App roots dir (same as SYS_PATH if app not a child
define("SYS_PATH", SELF_PATH); //Path to Parent App Dir if any (if no parent, App's root dir
define("GLOBAL_PATH", SELF_PATH . "global/"); //Path to Network global Dir

define("INCLUDE_PATH", SELF_PATH . "includes/");
define("CLASS_PATH", GLOBAL_PATH . "classes/");
define("LIB_PATH", SELF_PATH . "lib/");
define("TEMPLATE_PATH", SELF_PATH . "templates/");
define("WEBAPP_CONFIG", INCLUDE_PATH . "app/app_config.php");

//Database
define("DB_PREFIX", "fh_");
define("DB_NAME", "gethwjpj_server");
define("DB_USER", "gethwjpj_server");
define("DB_PASS", "^r(L89r@Rmsb");
define("DB_HOST", "localhost");

//Session
define("USER_SESSION_HOLDER", "app_user");

//App constants
define("DEFAULT_TITLE", "Welcome");
define("TITLE_SUFFIX", " | Fragrance Homes & Realtors");

//Email constants
define("ALLOW_EMAIL", 0);
define("ADMIN_NAME", "Fragrance Homes & Realtors");
define("ADMIN_EMAIL", "server@c4cgrill.com");
define("ADMIN_DOMAIN", "c4cgrill.com");
define("SMTP_HOST", "c4cgrill.com");
define("SMTP_PORT", 465);
define("SMTP_ENCRYPT", ""); //"ssl", "tls"
define("SMTP_USERNAME", "server@c4cgrill.com");
define("SMTP_PASSWORD", "A\$smYVU=WvH*");

//reCaptcha Keys
define("RECAPTCHA_PUB_KEY", "...");
define("RECAPTCHA_PRIV_KEY", "...");