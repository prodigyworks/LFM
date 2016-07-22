<?php
	require_once('system-db.php');
	require_once('pdfreport.php');
	require_once("simple_html_dom.php");
	
	class HoursWorkedReport extends PDFReport {
		
		function AddPage($orientation='', $size='') {
			parent::AddPage($orientation, $size);
			
			$this->Image("images/logomain2.png", 173.6, 1);
			
			$size = $this->addText( 10, 13, "Hours Worked - Monthly", 12, 4, 'B') + 5;
			$this->SetFont('Arial','', 8);
				
			$cols = array( 
					"Customer"  => 155,
					"Hours Worked"  => 35,
				);
			
			$this->addCols($size, $cols);

			$cols = array(
					"Customer"  => "L",
					"Hours Worked"  => "R"
				);
			$this->addLineFormat( $cols);
			$this->SetY(29);
		}
		
		function __construct($orientation, $metric, $size, $year, $month) {
			$dynamicY = 0;
			
	        parent::__construct($orientation, $metric, $size);
	        
	        $this->SetAutoPageBreak(true, 30);
	        
			$this->AddPage();
			
			try {
				$sql = "SELECT SUM(TIMESTAMPDIFF(MINUTE, starttime, endtime)) AS hours,  B.name AS customername
						FROM {$_SESSION['DB_PREFIX']}diary A 
						INNER JOIN {$_SESSION['DB_PREFIX']}client B 
						ON B.id = A.clientid 
						WHERE A.status IN ('I', 'C')
						AND YEAR(A.starttime) = $year
						AND MONTH(A.starttime) = $month
						AND A.deleted != 'Y'
						GROUP BY B.name
						ORDER BY B.name";
				$result = mysql_query($sql);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						$line=array(
								"Customer"  => $member['customername'],
								"Hours Worked"  => number_format($member['hours'] / 60, 2)
							);
							
						if ($this->GetY() > 260) {
							$this->AddPage();
						}
							
						$this->addLine( $this->GetY(), $line, 5.5);
						$this->Line( 10, $this->GetY() - 0.5, 200, $this->GetY() - 0.5);
					}
					
				} else {
					logError($sql . " - " . mysql_error());
				}
				
			} catch (Exception $e) {
				logError($e->getMessage());
			}
		}
	}
	
	start_db();
	
	$pdf = new HoursWorkedReport( 'P', 'mm', 'A4', $_POST['year'], $_POST['month']);
	$pdf->Output();
?>