<?php
	require_once("crud.php");
	
	class ClientCrud extends Crud {
		public $clientid;
		public $weekday;
		public $enddate;
		public $staffid;
		public $starttime;
		public $endtime;
		
		private function getWeekDay($weekday) {
			if ($weekday == 0) return "Sunday";
			if ($weekday == 1) return "Monday";
			if ($weekday == 2) return "Tuesday";
			if ($weekday == 3) return "Wednesday";
			if ($weekday == 4) return "Thursday";
			if ($weekday == 5) return "Friday";
			if ($weekday == 6) return "Saturday";
			
			return "N/A";
		}
		
		public function preUpdateEvent($id) {
			$sql = "SELECT *
					FROM {$_SESSION['DB_PREFIX']}clientschedule A
					WHERE id = $id";
			$result = mysql_query($sql);

			if ($result) {
				while (($member = mysql_fetch_assoc($result))) {
					$this->staffid = $member['memberid'];
					$this->enddate = $member['canceldate'];
					$this->weekday = $member['weekday'];
					$this->starttime = $member['starttime'];
					$this->endtime = $member['endtime'];
				}
			}
		}
		
		public function postInsertEvent() {
			$this->notifyCleaner(mysql_insert_id());
		}
		
		public function postUpdateEvent($id) {
			$this->notifyCleaner($id);
		}
		
		public function notifyCleaner($id) {
			$sql = "SELECT A.name AS clientname
					FROM {$_SESSION['DB_PREFIX']}client A
					WHERE id = " . $this->clientid;
			$result = mysql_query($sql);

			if ($result) {
				while (($member = mysql_fetch_assoc($result))) {
					$memberid = $_POST['memberid'];
					$enddate = convertStringToDate($_POST['canceldate']);
					
					if ($enddate == "") {
						$enddate = "0000-00-00";
					}
					
					if ($this->weekday != $_POST['weekday'] ||
						$this->staffid != $_POST['memberid']) {
							
//						logError("WEEK DAY OR STAFF CHANGED", false);
							
						$clientname = $member['clientname'];
						$lines = "Shift allocated to you on " . $this->getWeekDay($_POST['weekday']) . " " . $_POST['starttime'] . "-" . $_POST['endtime'] . " for $clientname\n";
		    			
		    			sendUserMessage($memberid, "Work Allocation", $lines);
					}
	    			
					if ($this->weekday != $_POST['weekday']) {
//						logError("WEEK DAY CHANGED", false);
						$sql = "DELETE FROM {$_SESSION['DB_PREFIX']}diary 
		    					WHERE scheduleid = $id
		    					AND status = 'U'
		    					AND deleted != 'Y'";
		    			$itemresult = mysql_query($sql);
		    			
		    			if (! $itemresult) {
		    				logError($sql . " - " . mysql_error());
		    			}
		    			
					} else if ($this->staffid != $_POST['memberid']) {
//						logError("STAFF CHANGED", false);
						$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary 
								SET memberid = $memberid
		    					WHERE scheduleid = $id
		    					AND status = 'U'
		    					AND deleted != 'Y'";
		    			$itemresult = mysql_query($sql);
		    			
		    			if (! $itemresult) {
		    				logError($sql . " - " . mysql_error());
		    			}
					}
					
					
					if ($this->enddate != $enddate) {
//						logError("END DATE CHANGED: $enddate" . " - " . $this->enddate, false);
						$sql = "DELETE FROM {$_SESSION['DB_PREFIX']}diary 
		    					WHERE scheduleid = $id
		    					AND starttime > '$enddate'
		    					AND status = 'U'
		    					AND deleted != 'Y'";
		    			$itemresult = mysql_query($sql);
		    			
		    			if (! $itemresult) {
		    				logError($sql . " - " . mysql_error());
		    			}
					}
					
					if ($this->starttime != $_POST['starttime'] || $this->endtime != $_POST['endtime']) {
					    $starttime = $_POST['starttime'];
					    $endtime = $_POST['endtime'];
//						logError("STAFF CHANGED", false);
						$sql = "UPDATE {$_SESSION['DB_PREFIX']}diary 
								SET starttime = '$starttime',
								endtime = '$endtime'
		    					WHERE scheduleid = $id
		    					AND status = 'U'
		    					AND deleted != 'Y'";
		    			$itemresult = mysql_query($sql);
		    			
		    			if (! $itemresult) {
		    				logError($sql . " - " . mysql_error());
		    			}
		    			
					} 
				}
			}
		}
		
		/* Post script event. */
		public function postScriptEvent() {
?>
			var cleanerID = 0;
			var cleanerName = ""
			var cleanerTime = "";
			
			function preDeleteScriptEvent(id) {
				callAjax(
					"finddata.php", 
					{ 
						sql: "SELECT A.memberid, A.weekday, B.name " +
							 "FROM <?php echo $_SESSION['DB_PREFIX'];?>clientschedule A " + 
							 "INNER JOIN <?php echo $_SESSION['DB_PREFIX'];?>client B " + 
							 "ON B.id = A.clientid " + 
							 "WHERE A.id = " + id
					},
					function(data) {
						if (data.length > 0) {
							cleanerID = data[0].memberid;
							cleanerTime = data[0].weekday;
							cleanerName = data[0].name;

						} else {
							cleanerID = 0;
						}
					}
				);	
				
				return true;			
			}
			
			function postDeleteScriptEvent(id) {
				callAjax(
						"scheduledelete.php", 
						{ 
							id: id,
							cleanerid: cleanerID,
							clientname: cleanerName,
							weekday: cleanerTime
						},
						function(data) {
						},
						false
					);				
			}
		
			function mode_onchange() {
				if ($("#begindate").val() != "") {
					var parts = $("#begindate").val().split('/');
					var date = new Date(parts[2], parts[1] - 1, parts[0]);

					$("#weekday").val(date.getDay());
				}
			}
<?php
		}
	}
	
	$crud = new ClientCrud();
	$crud->clientid = $_GET['id'];
	$crud->preDeleteScriptEvent = "preDeleteScriptEvent";
	$crud->postDeleteScriptEvent = "postDeleteScriptEvent";
	$crud->title = "Client Schedule";
	$crud->table = "{$_SESSION['DB_PREFIX']}clientschedule";
	$crud->sql = "SELECT A.*, B.name, C.fullname
				  FROM  {$_SESSION['DB_PREFIX']}clientschedule A
				  INNER JOIN {$_SESSION['DB_PREFIX']}client B
				  ON B.id = A.clientid
				  LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}members C
				  ON C.member_id = A.memberid
				  WHERE A.clientid = " . $crud->clientid . "
				  ORDER BY A.weekday, A.starttime";
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
				'name'       => 'clientid',
				'datatype'	 => 'integer',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'editable' 	 => false,
				'default'	 => $crud->clientid,
				'label' 	 => 'Client'
			),
			array(
				'name'       => 'name',
				'length' 	 => 25,
				'bind'		 => false,
				'editable'	 => false,
				'label' 	 => 'Client'
			),
			array(
				'name'       => 'weekday',
				'length' 	 => 20,
				'onchange'	 => 'mode_onchange',
				'label' 	 => 'Weekday',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> '0',
							'text'		=> 'Sunday'
						),
						array(
							'value'		=> '1',
							'text'		=> 'Monday'
						),
						array(
							'value'		=> '2',
							'text'		=> 'Tuesday'
						),
						array(
							'value'		=> '3',
							'text'		=> 'Wednesday'
						),
						array(
							'value'		=> '4',
							'text'		=> 'Thursday'
						),
						array(
							'value'		=> '5',
							'text'		=> 'Friday'
						),
						array(
							'value'		=> '6',
							'text'		=> 'Saturday'
						)
					)
			),
			array(
				'name'       => 'mode',
				'length' 	 => 20,
				'onchange'	 => 'mode_onchange',
				'label' 	 => 'Schedule',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'O',
							'text'		=> 'One Off'
						),
						array(
							'value'		=> 'W',
							'text'		=> 'Weekly'
						),
						array(
							'value'		=> 'T',
							'text'		=> 'Two Weekly'
						),
						array(
							'value'		=> 'F',
							'text'		=> 'Four Weekly'
						)
					)
			),
			array(
				'name'       => 'begindate',
				'datatype'	 => 'date',
				'onchange'	 => 'mode_onchange',
				'length' 	 => 12,
				'label' 	 => 'Start From'
			),
			array(
				'name'       => 'canceldate',
				'datatype'	 => 'date',
				'required'	 => false,
				'length' 	 => 12,
				'label' 	 => 'End Date'
			),
			array(
				'name'       => 'starttime',
				'datatype'	 => 'time',
				'length' 	 => 12,
				'label' 	 => 'Start Time'
			),
			array(
				'name'       => 'endtime',
				'datatype'	 => 'time',
				'length' 	 => 12,
				'label' 	 => 'End Time'
			),
			array(
				'name'       => 'memberid',
				'type'       => 'DATACOMBO',
				'length' 	 => 18,
				'label' 	 => 'Default Cleaner',
				'table'		 => 'members',
				'required'	 => true,
				'table_id'	 => 'member_id',
				'alias'		 => 'fullname',
				'table_name' => 'fullname'
			)
		);

	$crud->run();
?>
