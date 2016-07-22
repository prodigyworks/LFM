<?php
	require_once("crud.php");
	
	class HolidayCrud extends Crud {
		
		public function postAddScriptEvent() {
			?>
			var myDate = new Date(); 
			var prettyDate =
					padZero(myDate.getDate()) + '/' +         
				    padZero((myDate.getMonth() + 1)) + '/' + 
					myDate.getFullYear(); 
					 
			$("#startdate").val(prettyDate).trigger("change");
			$("#enddate").val(prettyDate).trigger("change");
			$("#startdate_half").attr("checked", true).trigger("change");
			$("#enddate_half").attr("checked", true).trigger("change");
			<?php
		}
		
		/* Pre script event. */
		public function preScriptEvent() {
			?>
			var currentID = 0;
			<?php
		}
		
		public function postScriptEvent() {
?>

			function calculateDuration() {
				var startDateStr = $("#startdate").val();
				var endDateStr = $("#enddate").val();
				
				var startDate = new Date(startDateStr.substring(6, 10), (parseFloat(startDateStr.substring(3, 5)) - 1), startDateStr.substring(0, 2));
				var endDate = new Date(endDateStr.substring(6, 10), (parseFloat(endDateStr.substring(3, 5)) - 1), endDateStr.substring(0, 2));
				var days = workingDaysBetweenDates(startDate, endDate);
				
				if (days > 0) {
					if ($("#startdate_half").attr("checked") == false) {
						if (startDate.getDay() > 0 && startDate.getDay() < 6) {
							days -= 0.5;
						}
					}
					
					if ($("#enddate_half").attr("checked") == false) {
						if (endDate.getDay() > 0 && endDate.getDay() < 6) {
							days -= 0.5;
						}
					}
				}
				
				$("#daystaken").val(days);
			}
			
			function daysRemaining(node) {
				return node.holidayentitlement - node.daysremaining;
			}
			
			function duration(node) {
				return node.daystaken;
			}
			
<?php			
		}
		
		function __construct() {
			parent::__construct();
			
			$this->title = "Statutory Holidays";
			$this->table = "{$_SESSION['DB_PREFIX']}bankholiday";
			$this->dialogwidth = 500;

			$this->sql = 
				"SELECT A.*  
				 FROM {$_SESSION['DB_PREFIX']}bankholiday A 
				 ORDER BY startdate";

			$this->columns = array(
					array(
						'name'       => 'id',
						'length' 	 => 6,
						'pk'		 => true,
						'showInView' => false,
						'editable'	 => false,
						'bind' 	 	 => false,
						'filter'	 => false,
						'label' 	 => 'ID'
					),
					array(
						'name'       => 'name',
						'length' 	 => 55,
						'label' 	 => 'Name'
					),
					array(
						'name'       => 'startdate',
						'filter'	 => false,
						'datatype'	 => 'date',
						'associatedcolumns' => 
							array(
								'startdate_half'
							),
						'length' 	 => 15,
						'onchange'	 => 'calculateDuration',
						'label' 	 => 'Start Date'
					),
					array(
						'name'       => 'startdate_half',
						'type'	 	 => 'CHECKBOX',
						'showInView' => false,
						'filter'	 => false,
						'length' 	 => 15,
						'onchange'	 => 'calculateDuration',
						'label' 	 => 'Full Day'
					),
					array(
						'name'       => 'enddate',
						'filter'	 => false,
						'datatype'	 => 'date',
						'associatedcolumns' => 
							array(
								'enddate_half'
							),
						'length' 	 => 15,
						'onchange'	 => 'calculateDuration',
						'label' 	 => 'End Date'
					),
					array(
						'name'       => 'enddate_half',
						'filter'	 => false,
						'type'	 	 => 'CHECKBOX',
						'showInView' => false,
						'length' 	 => 15,
						'onchange'	 => 'calculateDuration',
						'label' 	 => 'Full Day'
					),
					array(
						'name'       => 'daystaken',
						'filter'	 => false,
						'align'	 	 => 'center',
						'length' 	 => 15,
						'readonly'	 => true,
						'required'	 => false,
						'label' 	 => 'Duration'
					)
				);
		}
	}

	$crud = new HolidayCrud();
	$crud->run();
?>