<?php
	require_once("crud.php");
	
	
	class DiaryCrud extends Crud {
		
		public function postScriptEvent() {
?>
			function loadComplete() {
			    var rowIds = $("#tempgrid").getDataIDs();
			    var today = new Date();
			    
			    for (i = 1; i <= rowIds.length; i++) {//iterate over each row
			        rowData = $("#tempgrid").jqGrid('getRowData', i);
	
			        if (rowData['status'] == 'Clocked In') {
			            $("#tempgrid").jqGrid('setRowData', i, false, "red-row");
			            
			        } else if (rowData['status'] == 'Clocked Out') {
			            $("#tempgrid").jqGrid('setRowData', i, false, "green-row");
			        }
			    }
			}		
<?php
		}
	}

	$crud = new DiaryCrud();
	$crud->allowAdd = false;
	$crud->allowEdit = false;
	$crud->postDataRefreshEvent = "loadComplete";
	$crud->title = "Diary";
	$crud->table = "{$_SESSION['DB_PREFIX']}diary";
	$crud->dialogwidth = 450;
	$crud->sql = 
			"SELECT A.*, B.fullname, C.name
			 FROM {$_SESSION['DB_PREFIX']}diary A 
			 INNER JOIN {$_SESSION['DB_PREFIX']}members B 
			 ON B.member_id = A.memberid 
			 LEFT OUTER	 JOIN {$_SESSION['DB_PREFIX']}client C 
			 ON C.id = A.clientid 
			 ORDER BY A.starttime DESC, A.status";
	
	$crud->columns = array(
			array(
				'name'       => 'id',
				'length' 	 => 6,
				'pk'		 => true,
				'showInView' => false,
				'editable'	 => false,
				'bind' 	 	 => false,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'clientid',
				'type'       => 'DATACOMBO',
				'length' 	 => 25,
				'label' 	 => 'Client',
				'table'		 => 'client',
				'required'	 => true,
				'table_id'	 => 'id',
				'alias'		 => 'name',
				'table_name' => 'name'
			),
			array(
				'name'       => 'memberid',
				'type'       => 'DATACOMBO',
				'length' 	 => 25,
				'label' 	 => 'Cleaner',
				'table'		 => 'members',
				'required'	 => true,
				'table_id'	 => 'member_id',
				'alias'		 => 'fullname',
				'table_name' => 'fullname'
			),
			array(
				'name'       => 'starttime',
				'datatype'	 => 'datetime',
				'length' 	 => 20,
				'label' 	 => 'Start Time'
			),
			array(
				'name'       => 'endtime',
				'datatype'	 => 'datetime',
				'length' 	 => 20,
				'label' 	 => 'End Time'
			),
			array(
				'name'       => 'actualstarttime',
				'datatype'	 => 'datetime',
				'length' 	 => 20,
				'label' 	 => 'Clocked In'
			),
			array(
				'name'       => 'actualendtime',
				'datatype'	 => 'datetime',
				'length' 	 => 20,
				'label' 	 => 'Clocked Out'
			),
			array(
				'name'       => 'status',
				'length' 	 => 10,
				'label' 	 => 'Status',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'U',
							'text'		=> 'Scheduled'
						),
						array(
							'value'		=> 'I',
							'text'		=> 'Clocked In'
						),
						array(
							'value'		=> 'C',
							'text'		=> 'Clocked Out'
						)
					)
			),
			array(
				'name'       => 'deleted',
				'length' 	 => 10,
				'label' 	 => 'Deleted',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'Y',
							'text'		=> 'Yes'
						),
						array(
							'value'		=> 'N',
							'text'		=> 'No'
						)
					)
			)
		);
	
		
	$crud->run();
?>
