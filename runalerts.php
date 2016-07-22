<?php
	require_once("system-db.php");
	
	start_db();
	
//	require_once("db-backup.php");
//	require_once("run-diary.php");
	
   	sendRoleMessage("ALERT", "Daily alert task schedule", "Information: Alerts task schedule run at " . date("d/m/Y"));
    	
	/********************************************************************* END OF SCHEDULE **************************************/
	$qry = "UPDATE {$_SESSION['DB_PREFIX']}siteconfig SET lastschedulerun = CURDATE(), metamodifieddate = NOW(), metamodifieduserid = " . getLoggedOnMemberID() . "";
	$result = mysql_query($qry);
	
	if (! $result) logError("Error: " . mysql_error(), false);
?>