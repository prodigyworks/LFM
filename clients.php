<?php
	require_once("crud.php");
	
	class ClientCrud extends Crud {
		
		/* Post header event. */
		public function postHeaderEvent() {
			createDocumentLink();
		}
		
		function postInsertEvent() {
			include("run-diary.php");
		}
		
		public function postScriptEvent() {
?>
			
			function editDocuments(node) {
				viewDocument(node, "addclientdocument.php", node, "clientdocs", "clientid");
			}
			
			function newStarterForm(node) {
				window.open("newstarterreport.php?id=" + node);
			}
<?php			
		}
	}
	
	$crud = new ClientCrud();
	$crud->dialogwidth = 950;
	$crud->title = "Client";
	$crud->table = "{$_SESSION['DB_PREFIX']}client";
	$crud->sql = "SELECT A.*
				  FROM  {$_SESSION['DB_PREFIX']}client A
				  ORDER BY A.name";
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
				'unique'	 => true,
				'name'       => 'name',
				'length' 	 => 30,
				'label' 	 => 'Name'
			),
			array(
				'name'       => 'status',
				'length' 	 => 10,
				'label' 	 => 'Status',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'L',
							'text'		=> 'Live'
						),
						array(
							'value'		=> 'I',
							'text'		=> 'Inactive'
						)
					)
			),
			array(
				'name'       => 'firstname',
				'length' 	 => 15,
				'label' 	 => 'First Name'
			),			
			array(
				'name'       => 'lastname',
				'length' 	 => 15,
				'label' 	 => 'Last Name'
			),			
			array(
				'name'       => 'address',
				'length' 	 => 12,
				'showInView' => false,
				'type'		 => 'BASICTEXTAREA',
				'label' 	 => 'Address'
			),
			array(
				'name'       => 'email',
				'length' 	 => 40,
				'label' 	 => 'Email'
			),
			array(
				'name'       => 'telephone',
				'length' 	 => 12,
				'label' 	 => 'Telephone'
			),
			array(
				'name'       => 'mobile',
				'length' 	 => 12,
				'required' 	 => false,
				'label' 	 => 'Mobile'
			),
			array(
				'name'       => 'frequency',
				'length' 	 => 20,
				'label' 	 => 'Frequency'
			),
			array(
				'name'       => 'contracttype',
				'length' 	 => 20,
				'label' 	 => 'Contract Type',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'A',
							'text'		=> 'A'
						),
						array(
							'value'		=> 'B',
							'text'		=> 'B'
						),
						array(
							'value'		=> 'C',
							'text'		=> 'C'
						)
					)
			),
			array(
				'name'       => 'startdate',
				'length' 	 => 12,
				'datatype'	 => 'date',
				'required' 	 => false,
				'label' 	 => 'Start Date'
			)
		);
		
	$crud->run();
?>
