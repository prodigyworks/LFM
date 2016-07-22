<?php 
	require_once("system-db.php");
	
	start_db();
	
	$startdate = date("Y-m-d");
	$enddate = date('Y-m-d', strtotime('+one year', $startdate));
	
	$sql = "SELECT id, name, frequency, contracttype, startdate
			FROM {$_SESSION['DB_PREFIX']}client 
			WHERE status = 'L' 
			ORDER BY name DESC";
	$result = mysql_query($sql);
	
	//Check whether the query was successful or not
	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$clientid = $member['id'];
			$date = $startdate;
			$clientname = $member['name'];
			$frequency = $member['frequency'];
			$contracttype = $member['contracttype'];
			$startofcontract = $member['startdate'];
			
			for ($year = 0; $year < 2; $year++) {
				$currentyear = date("Y") + $year;
				$startofyear = $currentyear . "-01-01";
				$endofyear = $currentyear . "-12-31";
				
				$sdate = $startofyear;
				$dStart = new DateTime($startofyear);
				$dEnd  = new DateTime($endofyear);
				$dDiff = $dStart->diff($dEnd);
				$diff = $dDiff->days;
				$days = floor($diff / $frequency);
				
//				echo "<p>$clientname</p>";
				
				for ($i = 0; $i < $frequency; $i++) {
					$add = true;
					
					if ($startofcontract != null && $startofcontract != "") {
						if (strtotime($sdate) < strtotime($startofcontract)) {
							$add = false;
						}
					}
				
					if ($add) {
//						echo "$i. Freq:$frequency - Diff $diff - Days: $days - Start:$sdate - Add:$add - Start:$startofyear - End:$endofyear<br>";

						$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}diary
								(
									clientid, status, starttime
								)
								VALUES
								(
									$clientid, 'U', '$sdate'
								)";
						/* Add record from template */
						$insertresult = mysql_query($sql);
		
						if (! $insertresult) {
							if (mysql_errno() != 1062) {
								logError(mysql_errno() . ": $sql - " . mysql_error());
							}
						}
					}
					
					$date = DateTime::createFromFormat('Y-m-d', $sdate);
					
					if ($contracttype == "B") {
						if ($date->format('m') >= 5 && $date->format('m') <= 10) {
							$date->modify("+" . ($days * 2) . " days");
							
						} else {
							$date->modify("+" . floor($days / 2) . " days");
						}
						
					} else if ($contracttype == "C") {
						if ($date->format('m') < 5 || $date->format('m') > 10) {
							$date->modify("+" . ($days * 2) . " days");
							
						} else {
							$date->modify("+" . floor($days / 2) . " days");
						}
						
					} else {
						/* Default type is A. */
						$date->modify("+$days days");
					}
					
					$sdate = $date->format('Y-m-d');
				}
			}
		}
				
	} else {
		logError($sql . " - " . mysql_error());
	}

	mysql_query("COMMIT");
?>