<?php 
	include("system-embeddedheader.php");
	
	$mode = $_GET['mode'];
?>
	<script src='./codebase/dhtmlxscheduler.js' type="text/javascript" charset="utf-8"></script>
	<script src='./codebase/ext/dhtmlxscheduler_timeline.js' type="text/javascript" charset="utf-8"></script>
	<script src='js/jquery.ui.timepicker.js'></script>
	<link rel='STYLESHEET' type='text/css' href='./codebase/dhtmlxscheduler_glossy.css'>
	<link rel="stylesheet" href="./codebase/ext/dhtmlxscheduler_ext.css" type="text/css" media="screen" title="no title" charset="utf-8">
	
	<style type="text/css" media="print">
		.entry {
			display: block;
			font-size:6px;
		}
		#scheduler_here {
			text-align: left ! important;
			padding: 0px ! important;
			margin: 0px ! important;
		}
		.toleft {
			display: inline-block;
			width:60px;
		}
		.toright {
			display: inline-block;
			float:right;
			width:53px;
			padding-right: 2px;
			padding-left: 2px;
			background-color: white;
		}
		.keyblock {
			width:10px;
			height:10px;
			border:1px solid black;
		}
		.yellow {
			background-color: yellow;
		}
		.blue {
			background-color: blue;
		}
		.grey {
			background-color: #E0E0E0;
		}
		.red {
			background-color: red;
		}
		.green {
			background-color: green;
		}
		.one_line{
			white-space:nowrap;
			overflow:hidden;
			padding-top:5px; padding-left:5px;
			text-align:left !important;
		}
		
		.dhx_cal_event_line  {
			font-size:9px;
			line-height:12px;
		}
	</style>
	
	<script type="text/javascript" charset="utf-8">
	
		$(document).ready(
				function() {
					init();
				}
			);

		function init() {
			modSchedHeight();
			
			scheduler.locale.labels.timeline_tab = "Timeline";
			scheduler.locale.labels.section_custom="Section";
			scheduler.config.details_on_create=false;
			scheduler.config.dblclick_create = false;
			scheduler.config.drag_in = false;	      	scheduler.attachEvent("onBeforeDrag",function(){return false;})
	      	scheduler.attachEvent("onDblClick",function(){return false;});
	      	scheduler.attachEvent("onClick",function(){return false;});
			scheduler.config.xml_date="%Y-%m-%d %H:%i";
			scheduler.attachEvent("onBeforeFolderToggle", function(section,isOpen,allSections){
			    //any custom logic here
			    return false;
			});			
			scheduler.config.first_hour = 6;
			scheduler.config.last_hour = 23;
			//===============
			//Configuration
			//===============
			var sections=[
<?php 
				if ($mode == "S") {
					$sql = "SELECT member_id AS id, fullname AS name FROM {$_SESSION['DB_PREFIX']}members ORDER BY fullname	";

				} else if ($mode == "C") {
					$sql = "SELECT id, name FROM {$_SESSION['DB_PREFIX']}client WHERE status = 'L' ORDER BY name";
				}
				
				$result = mysql_query($sql);
				$first = true;
			
				//Check whether the query was successful or not
				if($result) {
					while (($member = mysql_fetch_assoc($result))) {
						if ($first) {
							$first = false;
						} else {
							echo ", ";
						}
?>
						{key:<?php echo $member['id']; ?>, label:"<?php echo $member['name']; ?>"}
<?php
					}
				}
		
?>
			];
				
			scheduler.createTimelineView({
				name:	"timeline",
				 x_unit:"day",//measuring unit of the X-Axis.
			     x_date:"%D %d %M %y", //date format of the X-Axis
			     x_step:1,      //X-Axis step in 'x_unit's
			     x_size:7,      //X-Axis length specified as the total number of 'x_step's
			     x_start:0,     //X-Axis offset in 'x_unit's
			     x_length:7,    //number of 'x_step's that will be scrolled at a time
				y_unit:	sections,
				y_property:	"section_id",
				render:"bar"
			});
				
			//===============
			//Data loading
			//===============
			scheduler.config.lightbox.sections=[	
				{name:"description", height:130, map_to:"text", type:"textarea" , focus:true},
				{name:"custom", height:23, type:"select", options:sections, map_to:"section_id" },
				{name:"time", height:12, type:"time", map_to:"auto"}
			];
<?php 
			$date = new DateTime(date("Y-m-d"));
			$date->sub(new DateInterval("P" . ($date->format('w')) . "D"));
			$date->add(new DateInterval("P1D"));
?>			

			scheduler.init('scheduler_here',new Date("<?php echo $date->format('Y-m-d'); ?>"),"timeline");
			scheduler.setLoadMode("day");
			scheduler.config.show_loading = true;

			scheduler.load("clientevents.php?mode=<?php echo $mode; ?>","json",function(){
			    // alert("Data has been successfully loaded");
			    scheduler.updateCollection("sections",sections );

				window.print();
		    
			});
			var dp = new dataProcessor("clientevents.php");
			dp.init(scheduler);
		}
		
		function modSchedHeight(){
			var sch = document.getElementById("scheduler_here");
			sch.style.height = "960px";
			sch.style.width = "1200px";
			var contbox = document.getElementById("contbox");
//			contbox.style.width = "100px";
		}
	</script>
	<div style="height:0px;background-color:#3D3D3D;border-bottom:5px solid #828282;">
		<div id="contbox" style="float:left;color:white;margin:22px 75px 0 75px; overflow:hidden;font: 17px Arial,Helvetica;color:white">
		</div>
	</div>
	<!-- end. info block -->
	<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
		<div class="dhx_cal_navline">
			<div class="dhx_cal_date"></div>
		</div>
		<div class="dhx_cal_header">
		</div>
		<div class="dhx_cal_data">
		</div>		
	</div>
<?php 
	include("system-embeddedfooter.php");
?>