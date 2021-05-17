<?php
	if(is_object($USER)){
		$USER->logout();
		$APP->setSessionMsg('You have successfully logged out');
	}
	redirect(HOME_DIR);
?>