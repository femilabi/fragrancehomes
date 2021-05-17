<?php 
	// require_login("admin");
	if (isset($_GET["email"])) {
		$email = $_GET["email"];
		$file = INCLUDE_PATH."email_templates/".$email.".php";
		if (file_exists($file)) {
			include_once($file);
		} else echo "Email not found";
	} else echo "Invalid request";
	exit;
?>
