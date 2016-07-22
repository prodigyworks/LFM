<?php
	require_once("crud.php");
	
	class UserAgentCrud extends Crud {
		
		public function postScriptEvent() {
?>
			function getPicture(node) {
				if (node.imageid != "" && node.imageid != 0) {
					return "<img height=100 src='system-imageviewer.php?id=" + node.imageid + "' />";
				}
				
				return "";
			}
<?php
		}
	}
	
	$crud = new UserAgentCrud();
	$crud->dialogwidth = 550;
	$crud->title = "User Images";
	$crud->table = "{$_SESSION['DB_PREFIX']}memberimages";
	$crud->sql = "SELECT A.*, B.fullname
				  FROM  {$_SESSION['DB_PREFIX']}memberimages A
				  INNER JOIN {$_SESSION['DB_PREFIX']}members B
				  ON B.member_id = A.memberid
				  WHERE A.memberid = {$_GET['id']}
				  ORDER BY A.id";
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
				'default'	 => $_GET['id'],
				'label' 	 => 'Member'
			),
			array(
				'name'       => 'fullname',
				'length' 	 => 30,
				'bind'		 => false,
				'editable'	 => false,
				'filter'	 => false,
				'label' 	 => 'Name'
			),			
			array(
				'name'       => 'imageimg',
				'length' 	 => 20,
				'required'	 => false,
				'editable'   => false,
				'bind'		 => false,
				'type'		 => 'DERIVED',
				'function'	 => 'getPicture',
				'label' 	 => 'Image'
			),
			array(
				'name'       => 'imageid',
				'type'		 => 'IMAGE',
				'showInView' => false,
				'required'   => false,
				'length' 	 => 65,
				'label' 	 => 'Image'
			)
		);
	$crud->run();
?>
