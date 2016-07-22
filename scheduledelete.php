<?php
	require_once('system-db.php');
	
	start_db();
	
	$id = $_POST['id'];
	$memberid = $_POST['cleanerid'];
	$clientname = $_POST['clientname'];
	$weekday = $_POST['weekday'];
	
	if ($weekday == 0) $day = "Sunday";
	if ($weekday == 1) $day = "Monday";
	if ($weekday == 2) $day = "Tuesday";
	if ($weekday == 3) $day = "Wednesday";
	if ($weekday == 4) $day = "Thursday";
	if ($weekday == 5) $day = "Friday";
	if ($weekday == 6) $day = "Saturday";
			
	sendUserMessage($memberid, "Schedule change", "Client '$clientname' scheduled for '$day' has been cancelled");
	
	$qry = "DELETE FROM {$_SESSION['DB_PREFIX']}diary WHERE scheduleid = $id AND status = 'U'";
	$result = mysql_query($qry);
	
	if (! $result) {
		logError($qry . " - " . mysql_error());
	}
	
	mysql_query("COMMIT");
?>