<?php
	include("system-db.php");
	
	start_db();

	if (isMobileUserAgent()) {
		header("location: imageslider.php");
		
	} else {
		header("location: users.php");
	}
?>
