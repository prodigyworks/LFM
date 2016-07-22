<?php 
	$mode = "S";
	
	include("system-header.php");
	
	function clientmode() {
		global $mode;
		
		$mode = "C";
	}
		
	function staffmode() {
		global $mode;
		
		$mode = "S";
	}
	?>
	<script src='./codebase/dhtmlxscheduler.js' type="text/javascript" charset="utf-8"></script>
	<script src='./codebase/ext/dhtmlxscheduler_timeline.js' type="text/javascript" charset="utf-8"></script>
	<script src='js/jquery.ui.timepicker.js'></script>
	<link rel='STYLESHEET' type='text/css' href='./codebase/dhtmlxscheduler_glossy.css'>
	<link rel="stylesheet" href="./codebase/ext/dhtmlxscheduler_ext.css" type="text/css" media="screen" title="no title" charset="utf-8">
	
	<style type="text/css" media="screen">
		.entry {
			display: block;
			font-size:9px;
		}
		.toleft {
			display: inline-block;
			width:100%;
		}
		.toright {
			display: inline-block;
			float:right;
			width:71px;
			font-size:8px;
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
		.purple {
			background-color: purple;
		}
		.red {
			background-color: red;
		}
		.green {
			background-color: #55FF55;
		}
		.orange {
			background-color: orange;
		}
		.one_line{
			white-space:nowrap;
			overflow:hidden;
			padding-top:5px; padding-left:5px;
			text-align:left !important;
		}
		
		.dhx_cal_event_line  {
			font-size:11px;
			line-height:12px;
		}
	</style>
	
	<script type="text/javascript" charset="utf-8">
	
		$(document).ready(
				function() {
					init();

					$("#keydialog").dialog({
							modal: false,
							minWidth: 90,
							height:100,
							dialogClass: "kev",
							autoOpen: true,
							width: "auto",
							opacity: 0.4,
							position: ["center", "top"],
							overlay: { opacity: 0.3, background: "white" },
							title: "Key"
						});
							

					$("#editdialog").dialog({
							modal: true,
							dialogClass: "kev",
							autoOpen: false,
							width: "auto",
							opacity: 0.4,
							overlay: { opacity: 0.3, background: "white" },
							title: "Edit",
							buttons: {
								Ok: function() {
									callAjax(
											"updateschedule.php", 
											{ 
												id: $("#eventid").val(),
												status: $("#status").val(),
												clientid: $("#clientid").val(),
												memberid: $("#memberid").val(),
												startdate: $("#entrydate").val(),
												enddate: $("#entrydate").val(),
												starttime: $("#starttime").val(),
												endtime: $("#endtime").val()
											},
											function(data) {
											},
											false
										);

									scheduler.clearAll();
									scheduler.setCurrentView(null, "timeline");
									
									$("#editdialog").dialog("close");
								},
								"Remove": function() {
									callAjax(
											"removeschedule.php", 
											{ 
												id: $("#eventid").val()
											},
											function(data) {
											},
											false
										);

									scheduler.clearAll();
									scheduler.setCurrentView(null, "timeline");
									
									$("#editdialog").dialog("close");
								},
								Cancel: function() {
									$("#editdialog").dialog("close");
								}
							}
						});
							
				}
			);

		function init() {
			modSchedHeight();
			
			scheduler.locale.labels.timeline_tab = "Timeline";
			scheduler.locale.labels.section_custom="Section";
			scheduler.config.details_on_create=false;
			scheduler.config.dblclick_create = false;
			scheduler.config.drag_in = false;	      	
			
			scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
			    if (old_mode != mode || +old_date != +date)
			        scheduler.clearAll();
			    return true;
			});
			scheduler.attachEvent("onBeforeDrag",function(){return false;})
	      	scheduler.attachEvent("onDblClick",function(){return false;})
	      	scheduler.attachEvent("onClick",function(node) {
				callAjax(
						"finddata.php", 
						{ 
							sql: "SELECT A.*, " + 
								 "DATE_FORMAT(A.starttime, '%d/%m/%Y') AS startdate, " + 
								 "DATE_FORMAT(A.starttime, '%H:%i') AS starttime, " + 
								 "DATE_FORMAT(A.endtime, '%d/%m/%Y') AS enddate, " + 
								 "DATE_FORMAT(A.endtime, '%H:%i') AS endtime, " + 
								 "DATE_FORMAT(A.actualstarttime, '%d/%m/%Y') AS actualstartdate, " + 
								 "DATE_FORMAT(A.actualstarttime, '%H:%i') AS actualstarttime, " + 
								 "DATE_FORMAT(A.actualendtime, '%d/%m/%Y') AS actualenddate, " + 
								 "DATE_FORMAT(A.actualendtime, '%H:%i') AS actualendtime " + 
								 "FROM <?php echo $_SESSION['DB_PREFIX']; ?>diary A " +
								 "WHERE id = " + node
						},
						function(data) {
							if (data.length == 1) {
								var node = data[0];

								if (node.status == "U") {
									$("#entrydate").val(node.startdate);
									$("#starttime").val(node.starttime);
									$("#endtime").val(node.endtime);
									
								} else if (node.status == "I") {
									$("#entrydate").val(node.actualstartdate);
									$("#starttime").val(node.actualstarttime);
									$("#endtime").val(node.endtime);
									
								} else {
									$("#entrydate").val(node.actualstartdate);
									$("#starttime").val(node.actualstarttime);
									$("#endtime").val(node.actualendtime);
								}

								$("#status").val(node.status);
								$("#eventid").val(node.id);
								$("#clientid").val(node.clientid);
								$("#memberid").val(node.memberid);
							}
						},
						false
					);
		      		
		      		$("#editdialog").dialog("open");
		      		
			      	return false;
		      	});
	      	scheduler.attachEvent("onClick",function(){return false;})
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
				render:"bar",
				sort:function(a, b){
				    var val = a.true_start_date > b.true_start_date ? 1 : -1;

				    return val;
                }
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
			});
			var dp = new dataProcessor("clientevents.php");
			dp.init(scheduler);
		}
		
		function modSchedHeight(){
			var sch = document.getElementById("scheduler_here");
			sch.style.height = (document.body.offsetHeight - 20) + "px";
			var contbox = document.getElementById("contbox");
			contbox.style.width = (parseInt(document.body.offsetWidth)-300)+"px";
		}

		function clientmode() {
			call("clientmode");
		}

		function printSchedule() {
			window.open("clientplannerdetails.php?mode=<?php echo $mode; ?>");
		}

		function staffmode() {
			call("staffmode");
		}
	</script>
	<div onclick="printSchedule()" style="font-size:11px; position:absolute; left:140px; top:62px; z-index:100" class="dhx_cal_today_button">&nbsp;&nbsp;Print</div>
	<div onclick="clientmode()" style="font-size:11px; position:absolute; left:460px; top:62px; z-index:100" class="dhx_cal_today_button">&nbsp;&nbsp;Clients</div>
	<div onclick="staffmode()" style="font-size:11px; position:absolute; left:530px; top:62px; z-index:100" class="dhx_cal_today_button">&nbsp;Staff</div>
	<div style="height:0px;background-color:#3D3D3D;border-bottom:5px solid #828282;">
		<div id="contbox" style="float:left;color:white;margin:22px 75px 0 75px; overflow:hidden;font: 17px Arial,Helvetica;color:white">
		</div>
	</div>
	<!-- end. info block -->
	<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
		<div class="dhx_cal_navline">
			<div class="dhx_cal_prev_button">&nbsp;</div>
			<div class="dhx_cal_next_button">&nbsp;</div>
			<div class="dhx_cal_date"></div>
			<div class="dhx_cal_tab" name="month_tab" style="right:150px;"></div>
			<div class="dhx_cal_tab" name="day_tab" style="right:215px;"></div>
			<div class="dhx_cal_tab" name="timeline_tab" style="right:280px;"></div>
		</div>
		<div class="dhx_cal_header">
		</div>
		<div class="dhx_cal_data">
		</div>		
	</div>
	<div id="editdialog" class="modal">
		<input type="hidden" id="eventid" />
		<table>
			<tr>
				<td>
					Client
				</td>
				<td>
					<?php createCombo("clientid", "id", "name", "{$_SESSION['DB_PREFIX']}client"); ?>
				</td>
			</tr>
			<tr>
				<td>
					Cleaner
				</td>
				<td>
					<?php createCombo("memberid", "member_id", "fullname", "{$_SESSION['DB_PREFIX']}members"); ?>
				</td>
			</tr>
			<tr>
				<td>
					Date
				</td>
				<td>
					<input class="datepicker" type="text" id="entrydate"  />
				</td>
			</tr>
			<tr>
				<td>
					Start Time
				</td>
				<td>
					<input class="timepicker" type="text" id="starttime"  />
				</td>
			</tr>
			<tr>
				<td>
					End Time
				</td>
				<td>
					<input class="timepicker" type="text" id="endtime"  />
				</td>
			</tr>
			<tr>
				<td>
					Status
				</td>
				<td>
				    <SELECT id="status" name="status">
				        <OPTION value="U">Scheduled</OPTION>
				        <OPTION value="I">Started</OPTION>
				        <OPTION value="C">Completed</OPTION>
				    </SELECT>
				</td>
			</tr>
		</table>
	</div>
	<div id="keydialog" class="modal">
		<table width='150px'>
			<tr>
				<td>Unallocated</td>
				<td>
					<div class="keyblock yellow">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Allocated</td>
				<td>
					<div class="keyblock green">&nbsp;</div>
				</td>
			</tr>
			<tr>
				<td>Complete</td>
				<td>
					<div class="keyblock red">&nbsp;</div>
				</td>
			</tr>
		</table>
	</div>
<?php 
	include("system-footer.php");
?>