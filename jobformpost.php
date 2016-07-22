<?php 
	require_once("system-db.php"); 
	require_once("sqlfunctions.php"); 
	
	start_db();
	
	$jobdate = convertStringToDate($_POST['jobdate']);
	$jobtime = mysql_escape_string($_POST['jobtime']);
	$jobaddress = mysql_escape_string($_POST['jobaddress']);
	$jobid = mysql_escape_string($_POST['jobid']);
	$jobclientref = mysql_escape_string($_POST['jobclientref']);
	$jobreftask = mysql_escape_string($_POST['jobreftask']);
	
	$jobactivity_rats = (isset($_POST['jobactivity_rats']) && $_POST['jobactivity_rats'] == "on") ? 1 : 0;
	$jobactivity_mice = (isset($_POST['jobactivity_mice']) && $_POST['jobactivity_mice'] == "on") ? 1 : 0;
	$jobactivity_cockroaches = (isset($_POST['jobactivity_cockroaches']) && $_POST['jobactivity_cockroaches'] == "on") ? 1 : 0;
	$jobactivity_wasps = (isset($_POST['jobactivity_wasps']) && $_POST['jobactivity_wasps'] == "on") ? 1 : 0;
	$jobactivity_foxes = (isset($_POST['jobactivity_foxes']) && $_POST['jobactivity_foxes'] == "on") ? 1 : 0;
	$jobactivity_birds = (isset($_POST['jobactivity_birds']) && $_POST['jobactivity_birds'] == "on") ? 1 : 0;
	$jobactivity_bitinginsects = (isset($_POST['jobactivity_bitinginsects']) && $_POST['jobactivity_bitinginsects'] == "on") ? 1 : 0;
	$jobactivity_crawlinginsects = (isset($_POST['jobactivity_crawlinginsects']) && $_POST['jobactivity_crawlinginsects'] == "on") ? 1 : 0;
	$jobactivity_nopests = (isset($_POST['jobactivity_nopests']) && $_POST['jobactivity_nopests'] == "on") ? 1 : 0;
	
	$jobvisittype = mysql_escape_string($_POST['jobvisittype']);
	$jobobservations = mysql_escape_string($_POST['jobobservations']);
	$jobactions = mysql_escape_string($_POST['jobactions']);
	$jobexcelets = mysql_escape_string($_POST['jobexcelets']);
	$jobmousedroppingspresent = mysql_escape_string($_POST['jobmousedroppingspresent']);
	$jobhygienefaults = mysql_escape_string($_POST['jobhygienefaults']);
	$jobvulnerable = mysql_escape_string($_POST['jobvulnerable']);
	$jobfoodsafety = mysql_escape_string($_POST['jobfoodsafety']);
	$jobproofingfaults = mysql_escape_string($_POST['jobproofingfaults']);
	$jobcomplete = mysql_escape_string($_POST['jobcomplete']);
	$jobexternalbaiting = mysql_escape_string($_POST['jobexternalbaiting']);
	$jobpesticides = mysql_escape_string($_POST['jobpesticides']);
	
	$jobimage1 = getImageData("jobimage1");
	$jobimage2 = getImageData("jobimage2");
	$jobimage3 = getImageData("jobimage3");
	$jobimage4 = getImageData("jobimage4");
	$clientname = mysql_escape_string($_POST['name']);
	
	try {
		$img = null;
		
		if (isset($_POST['output']) && $_POST['output'] != "") {
			$img = sigJsonToImage($_POST['output']);
		
		} else {
			// Create the image
			$img = imagecreatetruecolor(400, 30);
			
			// Create some colors
			$white = imagecolorallocate($img, 255, 255, 255);
			$grey = imagecolorallocate($img, 128, 128, 128);
			$black = imagecolorallocate($img, 0, 0, 0);
			imagefilledrectangle($img, 0, 0, 399, 29, $white);
			
			// The text to draw
			$text = $_POST['name'];
			// Replace path by your own font path
			$font = 'build/journal.ttf';
			
			// Add some shadow to the text
			imagettftext($img, 20, 0, 11, 21, $grey, $font, $text);
			
			// Add the text
			imagettftext($img, 20, 0, 10, 20, $black, $font, $text);
			
			// Using imagepng() results in clearer text compared with imagejpeg()
		}
		
		ob_start();
		imagepng($img);
		$imgstring = ob_get_contents(); 
        ob_end_clean();
		
		$escimgstring = mysql_escape_string($imgstring);
		
		$sql = "INSERT INTO {$_SESSION['DB_PREFIX']}images 
				(
					mimetype, name, image, createddate
				) 
				VALUES 
				(
					'image/png', 'Signture $jobid', '$escimgstring', NOW()
				)";
		$result = mysql_query($sql);
		
		if (! $result) {
			logError(mysql_errno() . ": $sql - " . mysql_error());
		}
		
		$imageid = mysql_insert_id();
		
	} catch (Exception $e) {
		$imageid = 0;
	}
	
	$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary SET
			jobdate = '$jobdate',
			jobtime = '$jobtime',
			jobaddress = '$jobaddress',
			jobclientref = '$jobclientref',
			jobreftask = '$jobreftask',
			jobactivity_rats = '$jobactivity_rats',
			jobactivity_mice = '$jobactivity_mice',
			jobactivity_cockroaches = '$jobactivity_cockroaches',
			jobactivity_wasps = '$jobactivity_wasps',
			jobactivity_foxes = '$jobactivity_foxes',
			jobactivity_birds = '$jobactivity_birds',
			jobactivity_bitinginsects = '$jobactivity_bitinginsects',
			jobactivity_crawlinginsects = '$jobactivity_crawlinginsects',
			jobactivity_nopests = '$jobactivity_nopests',
			jobvisittype = '$jobvisittype',
			jobobservations = '$jobobservations',
			jobactions = '$jobactions',
			jobexcelets = '$jobexcelets',
			jobmousedroppingspresent = '$jobmousedroppingspresent',
			jobhygienefaults = '$jobhygienefaults',
			jobvulnerable = '$jobvulnerable',
			jobfoodsafety = '$jobfoodsafety',
			jobproofingfaults = '$jobproofingfaults',
			jobcomplete = '$jobcomplete',
			jobexternalbaiting = '$jobexternalbaiting',
			jobpesticides = '$jobpesticides',
			jobimage1 = '$jobimage1',
			jobimage2 = '$jobimage2',
			jobimage3 = '$jobimage3',
			jobimage4 = '$jobimage4',
			status = 'C',
			imageid = $imageid,
			clientname = '$clientname',
			allocateddate = NOW()
			WHERE id = $jobid";
	
	$result = mysql_query($sql);
	
	if (! $result) {
		logError(mysql_errno() . ": $sql - " . mysql_error());
	}
	
	mysql_query("COMMIT");
	
	header("location: jobformconfirm.php");
?>