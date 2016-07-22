<?php include("system-mobileheader.php"); ?>
<script src="js/jquery.swipebox.js"></script>
<link rel="stylesheet" href="css/swipebox.css">
<style>
	.small-width {
	    max-width: 740px;
	}
	.wrap {
	    margin: 0 auto;
	    max-width: 1140px;
	    width: 92%;
	}
	ul li {
		list-style-type: none;
	}
	.centered, .small-width, blockquote {
	    margin-left: auto!important;
	    margin-right: auto!important;
	    display: block;
	    float: none!important;
	}

	.swipebox img {
	    -webkit-back-visibility: hidden;
	    display: block;
	    width: 150px;
	    padding:5px;
	    height: auto;
	    vertical-align: bottom;
	}
	body {
		background-color: black;
	}
	body img {
	    opacity: 1 !important;
	}
	form {
		padding:5px;
	}
</style>
	
	<table>
<?php 
	$memberid = getLoggedOnMemberID();
	$index = 0;
	$sql = "SELECT A.* 
			FROM {$_SESSION['DB_PREFIX']}memberimages A 
			WHERE A.memberid = $memberid";

	$result = mysql_query($sql);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			if (($index % 4) == 0) {
				if ($index > 0) {
					echo "<tr>\n";
				}
				
				echo "<tr>\n";
			}
?>
	    <td>
			<a href="system-imageviewer.php?id=<?php echo $member['imageid']; ?>" class="swipebox" title="Image">
				<img src="system-imageviewer.php?id=<?php echo $member['imageid']; ?>" alt="image">
			</a>
	    </td>
<?php	
			$index++;		
		}
		
	} else {
		logError($qry . " - " . mysql_error());
	}
?>
	  </tr>
	</table>


<script>
	$(document).ready(
			function() {
				$(".swipebox").swipebox( {
					useCSS : true, 
					useSVG : true, 
					initialIndexOnArray : 0, 
					hideCloseButtonOnMobile : false, 
					removeBarsOnMobile : true, 
					hideBarsDelay : 3000,
					videoMaxWidth : 1140, 
					beforeOpen: function() {},
					afterOpen: null, 
					afterClose: function() {},
					loopAtEnd: false
				} );			
			}
		);
</script>

<?php include("system-mobilefooter.php"); ?>
