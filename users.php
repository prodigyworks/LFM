<?php
	require_once("crud.php");
	
	function confirmPasswordChange() {
		$memberid = getLoggedOnMemberID();
		$password = mysql_escape_string(md5($_POST['postednewpassword']));
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}members SET 
				passwd = '$password', 
				metamodifieddate = NOW(), 
				metamodifieduserid = $memberid 
				WHERE member_id = {$_POST['expiredmemberid']}";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " = " . mysql_error());
		}
	}
	
	function expire() {
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}members SET status = 'N', metamodifieddate = NOW(), metamodifieduserid = " . getLoggedOnMemberID() . " WHERE member_id = " . $_POST['expiredmemberid'];
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " = " . mysql_error());
		}
	}
	
	function live() {
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}members SET status = 'Y', metamodifieddate = NOW(), metamodifieduserid = " . getLoggedOnMemberID() . " WHERE member_id = " . $_POST['expiredmemberid'];
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " = " . mysql_error());
		}
	}

	class UserCrud extends Crud {

		/* Pre command event. */
		public function preCommandEvent() {
			if (isset($_POST['rolecmd'])) {
				if (isset($_POST['roles'])) {
					$counter = count($_POST['roles']);

				} else {
					$counter = 0;
				}

				$memberid = $_POST['memberid'];
				$qry = "DELETE FROM {$_SESSION['DB_PREFIX']}userroles WHERE memberid = $memberid";
				$result = mysql_query($qry);

				if (! $result) {
					logError(mysql_error());
				}

				for ($i = 0; $i < $counter; $i++) {
					$roleid = $_POST['roles'][$i];

					$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}userroles (memberid, roleid, metacreateddate, metacreateduserid, metamodifieddate, metamodifieduserid) VALUES ($memberid, '$roleid', NOW(), " . getLoggedOnMemberID() . ", NOW(), " .  getLoggedOnMemberID() . ")";
					$result = mysql_query($qry);
				};
			}
		}

		public function postInsertEvent() {
			$this->persistImages(mysql_insert_id());
		}

		public function postUpdateEvent($id) {
			$this->persistImages($id);
		}

		public function persistImages($id) {
			$memberid = getLoggedOnMemberID();

			foreach ($_FILES['imageid']['name'] as $name => $value) {
				$binimage = file_get_contents($_FILES['imageid']['tmp_name'][$name]);
				$filename = $_FILES['imageid']['name'][$name];
				$size = getimagesize($_FILES['imageid']['tmp_name'][$name]);
				$width = $size[0];
				$height = $size[1];
				$mimetype = $_FILES['imageid']['type'][$name];

				$image = mysql_real_escape_string($binimage);
				$result = mysql_query("INSERT INTO {$_SESSION['DB_PREFIX']}images
									  (
									  		description, name, mimetype,
									  		image, imgwidth, imgheight,
									  		metacreateddate, metacreateduserid,
									  		metamodifieddate, metamodifieduserid
									  )
									  VALUES
									  (
									  		'$filename', '$filename',
									  		'$mimetype', '$image',
									  		$width, $height,
									  		NOW(), $memberid,
									  		NOW(), $memberid
									  )");

				if (!$result) {
					logError("Cannot persist image data ['$filename']:" . mysql_error());
				}

				$imageid = mysql_insert_id();

				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}memberimages
						(
							  memberid, imageid,
							  metacreateddate, metacreateduserid,
							  metamodifieddate, metamodifieduserid
						)
						VALUES
						(
							  $id, $imageid,
								NOW(), $memberid,
								NOW(), $memberid
						)";
				$result = mysql_query($qry);

				if (!$result) {
					logError("Cannot persist image data ['$filename']:" . mysql_error());
				}
			}
		}

		public function editScreenSetup() {
			include("userform.php");
		}

		/* Post header event. */
		public function postHeaderEvent() {
?>
			<script src='js/jquery.picklists.js' type='text/javascript'></script>

			<div id="pwdDialog" class="modal">
				<table cellspacing=10>
					<tr>
						<td>
							<label>New Password</label>
						</td>
						<td>
							<input type="password" id="newpassword" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Confirm Password</label>
						</td>
						<td>
							<input type="password" id="confirmnewpassword" />
						</td>
					</tr>
				</table>
			</div>
			<div id="roleDialog" class="modal">
				<form id="rolesForm" name="rolesForm" method="post">
					<input type="hidden" id="memberid" name="memberid" />
					<input type="hidden" id="rolecmd" name="rolecmd" value="X" />
					<select class="listpicker" name="roles[]" multiple="true" id="roles" >
						<?php createComboOptions("roleid", "roleid", "{$_SESSION['DB_PREFIX']}roles", "", false); ?>
					</select>
				</form>
			</div>
<?php
		}

		/* Post script event. */
		public function postScriptEvent() {
?>
			var currentRole = null;
			var currentID = null;

			function fullName(node) {
				return (node.firstname + " " + node.lastname);
			}

			$(document).ready(function() {
					$("#roles").pickList({
							removeText: 'Remove Role',
							addText: 'Add Role',
							testMode: false
						});
					
					$("#pwdDialog").dialog({
							autoOpen: false,
							modal: true,
							title: "Password",
							buttons: {
								Ok: function() {
									if ($("#newpassword").val() != $("#confirmnewpassword").val()) {
										pwAlert("Passwords do not match");
										return;
									}
									
									post("editform", "confirmPasswordChange", "submitframe", 
											{ 
												expiredmemberid: currentID,
												postednewpassword: $("#newpassword").val() 
											}
										);
									
									$(this).dialog("close");
								},
								Cancel: function() {
									$(this).dialog("close");
								}
							}
						});

					$("#roleDialog").dialog({
							autoOpen: false,
							modal: true,
							width: 800,
							title: "Roles",
							buttons: {
								Ok: function() {
									$("#rolesForm").submit();
								},
								Cancel: function() {
									$(this).dialog("close");
								}
							}
						});
				});

			function userRoles(memberid) {
				getJSONData('findroleusers.php?memberid=' + memberid, "#roles", function() {
					$("#memberid").val(memberid);
					$("#roleDialog").dialog("open");
				});
			}

			function changePassword(memberid) {
				currentID = memberid;
				
				$("#pwdDialog").dialog("open");
			}
				
			function expire(memberid) {
				post("editform", "expire", "submitframe",
						{
							expiredmemberid: memberid
						}
					);
			}

			function live(memberid) {
				post("editform", "live", "submitframe",
						{
							expiredmemberid: memberid
						}
					);
			}
<?php
		}
	}

	$crud = new UserCrud();
	$crud->messages = array(
			array('id'		  => 'expiredmemberid'),
			array('id'		  => 'postednewpassword')
		);
	$crud->subapplications = array(
			array(
				'title'		  => 'User Roles',
				'imageurl'	  => 'images/user.png',
				'script' 	  => 'userRoles'
			),
			array(
				'title'		  => 'Expire',
				'imageurl'	  => 'images/cancel.png',
				'script' 	  => 'expire'
			),
			array(
				'title'		  => 'Live',
				'imageurl'	  => 'images/heart.png',
				'script' 	  => 'live'
			),
			array(
				'title'		  => 'Change Password',
				'imageurl'	  => 'images/lock.png',
				'script' 	  => 'changePassword'
			),
			array(
				'title'		  => 'Images',
				'imageurl'	  => 'images/document.gif',
				'application' => 'manageuserimages.php'
			)
		);
		
	$crud->allowAdd = false;
	$crud->dialogwidth = 500;
	$crud->title = "Users";
	$crud->table = "{$_SESSION['DB_PREFIX']}members";
	
	$crud->sql = 
			"SELECT A.*
			 FROM {$_SESSION['DB_PREFIX']}members A 
			 ORDER BY A.firstname, A.lastname"; 
			
	$crud->columns = array(
			array(
				'name'       => 'member_id',
				'length' 	 => 6,
				'showInView' => false,
				'bind' 	 	 => false,
				'filter'	 => false,
				'editable' 	 => false,
				'pk'		 => true,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'login',
				'length' 	 => 15,
				'label' 	 => 'Login ID'
			),
			array(
				'name'       => 'staffname',
				'type'		 => 'DERIVED',
				'length' 	 => 45,
				'bind'		 => false,
				'function'   => 'fullName',
				'sortcolumn' => 'A.firstname',
				'label' 	 => 'Name'
			),
			array(
				'name'       => 'firstname',
				'length' 	 => 25,
				'showInView' => false,
				'label' 	 => 'First Name'
			),
			array(
				'name'       => 'lastname',
				'length' 	 => 25,
				'showInView' => false,
				'label' 	 => 'Last Name'
			),
			array(
				'name'       => 'email',
				'length' 	 => 50,
				'label' 	 => 'Email'
			),
			array(
				'name'       => 'mobile',
				'length' 	 => 13,
				'label' 	 => 'Mobile'
			),
			array(
				'name'       => 'status',
				'length' 	 => 12,
				'label' 	 => 'Status',
				'type'       => 'COMBO',
				'options'    => array(
						array(
							'value'		=> 'Y',
							'text'		=> 'Live'
						),
						array(
							'value'		=> 'N',
							'text'		=> 'Expired'
						)
					)
			),
			array(
				'name'       => 'passwd',
				'type'		 => 'PASSWORD',
				'length' 	 => 30,
				'showInView' => false,
				'label' 	 => 'Password'
			),
			array(
				'name'       => 'cpassword',
				'type'		 => 'PASSWORD',
				'length' 	 => 30,
				'bind' 	 	 => false,
				'showInView' => false,
				'label' 	 => 'Confirm Password'
			)
		);
				
	$crud->run();
?>
