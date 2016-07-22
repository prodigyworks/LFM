<?php
	require_once("system-db.php");
	require_once("crud.php");
	
	start_db();
	
	function assignMe() {
		$id = $_POST['jobid'];
		$memberid = getLoggedOnMemberID();
		
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}diary SET 
				memberid = $memberid,
				status = 'A'
				WHERE id = $id";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
	}
	
	class DashboardCrud extends Crud {
		
		public function afterInsertRow() {
?>
			var fullname = rowData['fullname'];

		   	if (fullname != null && fullname != ""){
				$(this).jqGrid('setRowData', rowid, false, { color: '#FF0000' });
		   	}
<?php
		}
		
		public function postScriptEvent() {
?>
			function assignMe(node) {
				post("editform", "assignMe", "submitframe", 
						{ 
							jobid: node
						}
					);
			}
			 
			$(document).ready(function() {
					$("#daysforward").change(
							function() {
								navigate("<?php echo $_SERVER['PHP_SELF']; ?>?daysforward=" + $(this).val());
							}
						);
						
					$("#cleardate").click(
							function() {
								navigate("<?php echo $_SERVER['PHP_SELF']; ?>");
							}
						);
<?php 
	if (isset($_GET['daysforward'])) {
?>						
					$("#daysforward").val("<?php echo $_GET['daysforward']; ?>");		
<?php 
	} else {
?>						
					$("#daysforward").val("5");		
<?php 
	}
?>						
				}
			);
<?php
		}
	
		/* Post header event. */
		public function postHeaderEvent() {
?>
			<style>
				#dateswitch {
					position: absolute;
					top: 38px;
					left: 400px;
					width:200px;
					height:32px;
				}
				#cleardate {
					width:16px;
					height:16px;
				}
			</style>
			<div id="dateswitch">
				<span>Select Days Ahead</span>
				<SELECT id="daysforward" name="daysforward">
					<OPTION value="1">1</OPTION>
					<OPTION value="2">2</OPTION>
					<OPTION value="3">3</OPTION>
					<OPTION value="4">4</OPTION>
					<OPTION value="5">5</OPTION>
					<OPTION value="6">6</OPTION>
					<OPTION value="7">7</OPTION>
					<OPTION value="14">14</OPTION>
					<OPTION value="31">31</OPTION>
					<OPTION value="56">56</OPTION>
				</SELECT>
				<span id="cleardate"><img src='images/delete.png' /></span>
			</div>
<?php
		}
	}
	
	$daysforward = 5;
	
	if (isset($_GET['daysforward'])) {
		$daysforward = $_GET['daysforward'];
	}
	
	$boundary = date("Y-m-d", strtotime("+" . $daysforward . " day"));
	
	$crud = new DashboardCrud();
	$crud->dialogwidth = 650;
	$crud->allowAdd = false;
	$crud->allowRemove = false;
	$crud->allowEdit = false;
	$crud->allowView = false;
	$crud->title = "Dashboard";
	$crud->table = "{$_SESSION['DB_PREFIX']}diary";
	$crud->sql = "SELECT A.*, A.id AS jobid, B.name, B.id AS siteid, C.fullname
				  FROM  {$_SESSION['DB_PREFIX']}diary A
				  INNER JOIN {$_SESSION['DB_PREFIX']}client B
				  ON B.id = A.clientid
				  LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}members C
				  ON C.member_id = A.memberid
				  WHERE '$boundary' >= A.starttime
				  AND B.status = 'L'
				  AND A.status != 'C'
				  ORDER BY A.starttime, B.name";
	$crud->columns = array(
			array(
				'name'       => 'id',
				'viewname'   => 'uniqueid',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'bind' 	 	 => false,
				'editable' 	 => false,
				'pk'		 => true,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'starttime',
				'length' 	 => 15,
				'datatype'	 => 'date',
				'label' 	 => 'Due Date'
			),			
			array(
				'name'       => 'siteid',
				'length' 	 => 10,
				'align'		 => 'right',
				'label' 	 => 'Site ID'
			),
			array(
				'name'       => 'name',
				'length' 	 => 35,
				'label' 	 => 'Site Name'
			),			
			array(
				'name'       => 'jobid',
				'length' 	 => 10,
				'align'		 => 'right',
				'label' 	 => 'Job ID'
			),
			array(
				'name'       => 'fullname',
				'length' 	 => 24,
				'label' 	 => 'Engineer'
			)
		);
		
	$crud->messages = array(
			array('id'		  => 'jobid')
		);
		
	$crud->subapplications = array(
			array(
				'title'		  => 'Assign Me',
				'imageurl'	  => 'images/team.png',
				'script'	  => 'assignMe'
			)
		);
		
	$crud->run();
?>
