<?php 
	include("system-db.php");
	
	start_db();
	
	header('Content-Type: application/json');
	
	if (! isset($_POST['start'])) {
		$startdate = "2014-07-01";
		$enddate = "2014-08-01";
				
	} else {
		$startdate = $_POST['start'];
		$enddate = $_POST['end'];
	}
//	
//	$sql = "SELECT B.fromdate, C.id, B.id AS diaryid, B.fromtime, B.totime, 
//			B.todate, A.firstname, A.lastname
//			FROM {$_SESSION['DB_PREFIX']}members A
//			INNER JOIN {$_SESSION['DB_PREFIX']}miscdiary B
//			ON B.memberid = A.member_id
//			WHERE A.status = 'Y' 
//			AND (B.fromdate <= '$enddate' AND B.todate >= '$startdate')";
//	$result = mysql_query($sql);	
//
//	$json = array();
//
//	if($result) {
//		while (($member = mysql_fetch_assoc($result))) {
//			$line = array(
//					"id"				=> $member['diaryid'], 
//					"allDay"			=> "false",
//					"className"			=> "eventcat_" . $member['id'],
//					"start"				=> $member['fromdate'] . " " . $member['fromtime'],
//					"end"				=> $member['todate'] . " " . $member['totime'],
//					"title"				=> $member['categoryname'] . " (" . $member['firstname'] . " " . $member['lastname'] . ")"
//				);
//				
//			array_push($json, $line);
//		}
//		
//	} else {
//		logError($qry . " - " . mysql_error());
//	}

	echo json_encode($json);
?>
