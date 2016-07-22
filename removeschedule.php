<?php
	require_once("system-db.php");
	
	start_db();
	
	$id = $_POST['id'];
	
	$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary  
			SET deleted = 'Y'
			WHERE id = $id";
	if (! mysql_query($sql)) {
		logError($sql . " - " . mysql_error());
	}
	
	$sql = "SELECT DATE_FORMAT(A.starttime, '%d/%m/%Y %H:%i') AS starttime, A.memberid,
			B.name AS clientname
			FROM {$_SESSION['DB_PREFIX']}diary A
			INNER JOIN {$_SESSION['DB_PREFIX']}client B
			ON B.id = A.clientid
			WHERE id = $id";
	$result = mysql_query($sql);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$memberid = $member['id'];
			$date = $member['starttime'];
			$clientname = $member['clientname'];
			
			sendUserMessage($memberid, "Cancellation", "The shift allocated to you on the $date for $clientname has been cancelled");
		}
	}
	
	mysql_query("COMMIT");
?>