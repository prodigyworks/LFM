<?php
	require_once("system-db.php");
	
	start_db();
	
	$id = $_POST['id'];
	$now = date("Y-m-d H:i:s");
	
	$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary SET 
			actualendtime = '$now',
			status = 'C'
			WHERE id = $id";
	
	if (! mysql_query($sql)) {
		logError($sql . " - " . mysql_error());
	}
	
	mysql_query("COMMIT");
		
	echo json_encode(
			array("checkout" => substr($now, 11, 5))
		);
	?>