<?php
	include("system-db.php");
	
	start_db();

	if (isMobileUserAgent()) {
		header("location: imageslider.php");
		
	} else {
		if (isUserInRole("ADMIN")) {
			header("location: users.php");
			
		} else {
			session_unset();
			header("location: system-login.php");
		}
	}
?>
