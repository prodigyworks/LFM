<?php 
	include "system-db.php";
	
	function getTimeDifference($starttime, $endtime) {
		$startmins = (substr($starttime, 0, 2) * 60) + substr($starttime, 3, 2);
		$endmins = (substr($endtime, 0, 2) * 60) + substr($endtime, 3, 2);
		$diff = $endmins - $startmins;
		$time = "";
		
		$hours = number_format(($diff / 60), 1) . " hrs";
		
		return $hours;
	}
	
	start_db();
	
	$startdate = ($_GET['from']);
	$enddate = ($_GET['to']);
	$json = array();
	$memberarray = array();
	$emailarray = array();
	
	if ($_GET['mode'] == "S") {
		$sectionid = "memberid";

	} else if ($_GET['mode'] == "C") {
		$sectionid = "clientid";
		
	} else {
		$sectionid = null;
		exit();
	}
	
	$sql = "SELECT id, name FROM {$_SESSION['DB_PREFIX']}client 
			WHERE status = 'L' 
			ORDER BY name DESC";
	$result = mysql_query($sql);
	
	//Check whether the query was successful or not
	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$clientid = $member['id'];
			$clientname = $member['name'];
				
			$sql = "SELECT A.*, B.name, C.fullname
					FROM {$_SESSION['DB_PREFIX']}diary A 
					INNER JOIN {$_SESSION['DB_PREFIX']}client B
					ON B.id = A.clientid 
					LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}members C
					ON C.member_id = A.memberid 
					WHERE A.deleted = 'N'
					AND A.clientid = $clientid
					AND A.starttime >= '$startdate' 
					AND A.starttime <= '$enddate'
					ORDER BY A.starttime";
			$itemresult = mysql_query($sql);
			
			//Check whether the query was successful or not
			if($itemresult) {
				while (($itemmember = mysql_fetch_assoc($itemresult))) {
					if ($itemmember['status'] == "U") {
						$color = "yellow";
						$description = "Unallocated";
						
					} else {
						$color = "#55FF55";
						
						if ($sectionid == "clientid") {
							$description = $itemmember['fullname'];
	
						} else {
							$description = $itemmember['name'];
						}
					}
					
					
					array_push(
							$json, 
							array(
									"id" => $itemmember['id'],
									"color" => $color,
									"textColor" => "black",
    								"true_start_date" => "$startdate",
									"start_date" => $startdate . " 00:00:00",
									"end_date" => $startdate . " 23:59:59",
									"text" => "<div class='entry'><span class='toleft'>$description</span><span class='toright'></span></div>",
									"section_id" => $itemmember[$sectionid]
								)
						);
				}
				
			} else {
				logError($sql . " - " . mysql_error());
			}
			
		}
				
	} else {
		logError($sql . " - " . mysql_error());
	}

	mysql_query("COMMIT");
	
	echo json_encode($json);
?>