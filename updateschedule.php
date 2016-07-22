<?php
	require_once("system-db.php");
	
	start_db();
	
	$id = $_POST['id'];
	$clientid = $_POST['clientid'];
	$memberid = $_POST['memberid'];
	$startdate = convertStringToDate($_POST['startdate']) . " " . $_POST['starttime'];
	$enddate = convertStringToDate($_POST['enddate']) . " " . $_POST['endtime'];
	$status = $_POST['status'];
	
	if ($status == "U") {
		$sql = "SELECT DATE_FORMAT(A.starttime, '%d/%m/%Y %H:%i') AS starttime, A.memberid,
				B.name AS clientname, C.name AS originalclientname
				FROM {$_SESSION['DB_PREFIX']}diary A
				INNER JOIN {$_SESSION['DB_PREFIX']}client B
				ON B.id = A.clientid
				INNER JOIN {$_SESSION['DB_PREFIX']}client C
				ON C.id = $clientid
				WHERE id = $id";
		$result = mysql_query($sql);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$memberid = $member['id'];
				$originaldate= $member['starttime'];
				$originalclient = $member['clientname'];
				$date = $_POST['startdate'];
				$clientname = $_POST['originalclientname'];
				
				sendUserMessage($memberid, "Cancellation", "The shift originally allocated on $originaldate for $originalclient has been moved to $date for $clientname");
			}
		}
		
		$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary SET 
				starttime = '$startdate', 
				endtime = '$enddate', 
				memberid = $memberid,
				status = '$status',
				clientid = $clientid
				WHERE id = $id";

	} else if ($status == "I") {
		$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary SET 
				actualstarttime = '$startdate', 
				status = '$status',
				endtime = '$enddate', 
				memberid = $memberid,
				clientid = $clientid
				WHERE id = $id";

	} else {
		$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary SET 
				actualstarttime = '$startdate', 
				actualendtime = '$enddate', 
				status = '$status',
				memberid = $memberid,
				clientid = $clientid
				WHERE id = $id";
	}
	
	if (! mysql_query($sql)) {
		logError($sql . " - " . mysql_error());
	}
	
	mysql_query("COMMIT");
?>