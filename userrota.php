<?php
	require_once("crud.php");
	
	class UserRotaCrud extends Crud {
		
	}
	
	$memberid = $_GET['id'];
	
	$crud = new UserRotaCrud();
	$crud->title = "User Rota";
	$crud->table = "{$_SESSION['DB_PREFIX']}memberrota";
	$crud->sql = "SELECT A.*, B.fullname
				  FROM  {$_SESSION['DB_PREFIX']}memberrota A
				  LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}members B
				  ON B.member_id = A.memberid
				  WHERE A.memberid = $memberid
				  ORDER BY A.weekday";
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
				'name'       => 'memberid',
				'datatype'	 => 'integer',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'editable' 	 => false,
				'default'	 => $memberid,
				'label' 	 => 'Client'
			),
			array(
				'name'       => 'fullname',
				'length' 	 => 25,
				'bind'		 => false,
				'editable'	 => false,
				'label' 	 => 'Name'
			),
			array(
				'name'       => 'weekday',
				'length' 	 => 20,
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
			)
		);

	$crud->run();
?>
