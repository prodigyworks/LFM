<?php
	require_once('system-db.php');
	
	if(!isset($_SESSION)) {
		session_start();
	}
	
	if (! isAuthenticated() && ! endsWith($_SERVER['PHP_SELF'], "system-login.php")) {
		
		header("location: m.system-login.php?session=" . urlencode(base64_encode($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] )));
		exit();
	}
?>
<?php 
	//Include database connection details
	require_once('system-config.php');
	require_once("confirmdialog.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>London Fashion Models</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<link rel="shortcut icon" href="favicon.ico">

<script src="js/ios-orientationchange-fix.js"></script>
<script src="js/jquery-2.1.0.min.js"></script>

<link href="css/m.style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<?php
		if (isset($_POST['command'])) {
			$_POST['command']();
		}
	?>
	
	<form method="post" id="commandForm" name="commandForm">
		<input type="hidden" id="command" name="command" />
		<input type="hidden" id="pk1" name="pk1" />
		<input type="hidden" id="pk2" name="pk2" />
	</form>
		<div id="embeddedcontent">
			<div class="embeddedpage">
				<div class="title"><?php echo $_SESSION['title']; ?></div>
				<div class="logout">
					<a href="system-logout.php"><img src='images/logout.png' /></a>
				</div>
				<hr>

			