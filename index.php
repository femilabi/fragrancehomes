<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
exit();
session_start();
require_once(dirname(__FILE__) . "/interface.php");
date_default_timezone_set("GMT");

//time session out
if (!(isset($_SESSION["timeoutlastvisit"]) && (time() - $_SESSION["timeoutlastvisit"]) < (60 * 24 * 24))) {
	session_destroy();
	session_start();
}
$_SESSION["timeoutlastvisit"] = time();

if (isset($_SESSION[USER_SESSION_HOLDER]) && is_array($_SESSION[USER_SESSION_HOLDER]) && isset($_SESSION[USER_SESSION_HOLDER]["id"])) {
	$USER = new User($_SESSION[USER_SESSION_HOLDER]["id"]);
}

//Get content type header for output
$headers = getallheaders();
$content_type = @$headers["contentType"];
if ($content_type == "json" || @$_GET["contentType"] == "json") $APP->setIsJSON(true);
if ($content_type == "html" || @$_GET["contentType"] == "html") $APP->setIsAJAX(true);

//Output content
$APP->execute();
$APP->printOutput();
$DB->close();
