<?php
	require_once('system-db.php');
	require_once('pdfreport.php');
	require_once("simple_html_dom.php");
	
	class HoursWorkedReport extends PDFReport {
		
		function AddPage($orientation='', $size='') {
			parent::AddPage($orientation, $size);
			
			$this->Image("images/logomain2.png", 173.6, 1);
			
			$size = $this->addText( 10, 13, "Hours - Staff", 12, 4, 'B') + 5;
			$this->SetFont('Arial','', 8);
				
			$cols = array( 
					"Staff Member"  => 115,
					"Holidays"  => 25,
					"Absences"  => 25,
					"Hours Worked"  => 25
				);
			
			$this->addCols($size, $cols);

			$cols = array(
					"Staff Member"  => "L",
					"Holidays"  => "R",
					"Absences"  => "R",
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
			    $startdate = "$year-$month-01";
			    $enddate = "$year-$month-" . cal_days_in_month(CAL_GREGORIAN, $month, $year);
			    
				$sql = "SELECT SUM(TIMESTAMPDIFF(MINUTE, starttime, endtime)) AS hours,  B.fullname AS customername, B.member_id
						FROM {$_SESSION['DB_PREFIX']}diary A 
						INNER JOIN {$_SESSION['DB_PREFIX']}members B 
						ON B.member_id = A.memberid 
						WHERE A.status IN ('I', 'C')
						AND YEAR(A.starttime) = $year
						AND MONTH(A.starttime) = $month
						GROUP BY B.fullname
						ORDER BY B.fullname";
				$result = mysql_query($sql);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
					    
        			    $holidays = 0;
        			    $absences = 0;
        			    $memberid = $member['member_id'];
        			    
        				$sql = "SELECT A.startdate, A.enddate, A.enddate_half, A.startdate_half
        						FROM {$_SESSION['DB_PREFIX']}holiday A 
                                WHERE A.memberid = $memberid 
                                AND A.startdate <= '$enddate' 
                                AND A.enddate >= '$startdate'
        						UNION ALL
        						SELECT B.startdate, B.enddate, B.enddate_half, B.startdate_half
        						FROM {$_SESSION['DB_PREFIX']}bankholiday B
                                WHERE B.startdate <= '$enddate' 
                                AND B.enddate >= '$startdate'";
        				$itemresult = mysql_query($sql);

        				if ($itemresult) {
        					while (($itemmember = mysql_fetch_assoc($itemresult))) {
        					    $date1 = new DateTime($startdate);
                                $date2 = new DateTime($itemmember['startdate']);

                                $diff = $date2->diff($date1)->format("%a");

                                if ($diff > 0) {
                                    $realstartdate = $date2;
                                    
                                } else {
                                    $realstartdate = $date1;
                                }
                                
        					    $date1 = new DateTime($enddate);
                                $date2 = new DateTime($itemmember['enddate']);

                                $diff = $date1->diff($date2)->format("%a");
                                
                                if ($diff > 0) {
                                    $realenddate = $date2;
                                    
                                } else {
                                    $realenddate = $date1;
                                }
                                
                                $holidays += $realenddate->diff($realstartdate)->format("%a");
                                $date = $realstartdate->format("Y-m-d");
                                
                            	while (strtotime($date) <= strtotime($realenddate->format("Y-m-d"))) {
                            	    if (date("w", strtotime($date)) == 0 || date("w", strtotime($date)) == 6) {
                            	        $holidays--;
                            	    }
                            	    
                                    $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
                            	}
                            	
                                if ($itemmember['enddate_half'] == 1) {
                                    $holidays += 0.5;
                                }
                                
                                if ($itemmember['startdate_half'] == 1) {
                                    $holidays += 0.5;
                                }
        					}
        					
        				} else {
        					logError($sql . " - " . mysql_error());
        				}
        					
        				$sql = "SELECT *
        						FROM {$_SESSION['DB_PREFIX']}absence A 
                                WHERE memberid = $memberid 
                                AND startdate <= '$enddate' 
                                AND enddate >= '$startdate'";
        				$itemresult = mysql_query($sql);

        				if ($itemresult) {
        					while (($itemmember = mysql_fetch_assoc($itemresult))) {
        					    $date1 = new DateTime($startdate);
                                $date2 = new DateTime($itemmember['startdate']);

                                $diff = $date2->diff($date1)->format("%a");

                                if ($diff > 0) {
                                    $realstartdate = $date2;
                                    
                                } else {
                                    $realstartdate = $date1;
                                }
                                
        					    $date1 = new DateTime($enddate);
                                $date2 = new DateTime($itemmember['enddate']);

                                $diff = $date1->diff($date2)->format("%a");
                                
                                if ($diff > 0) {
                                    $realenddate = $date2;
                                    
                                } else {
                                    $realenddate = $date1;
                                }
                                
                                $absences += $realenddate->diff($realstartdate)->format("%a");
                                $date = $realstartdate->format("Y-m-d");
                                
                            	while (strtotime($date) <= strtotime($realenddate->format("Y-m-d"))) {
                            	    if (date("w", strtotime($date)) == 0 || date("w", strtotime($date)) == 6) {
                            	        $absences--;
                            	    }
                            	    
                                    $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
                            	}
                            	
                                if ($itemmember['enddate_half'] == 1) {
                                    $absences += 0.5;
                                }
                                
                                if ($itemmember['startdate_half'] == 1) {
                                    $absences += 0.5;
                                }
        					}
        					
        				} else {
        					logError($sql . " - " . mysql_error());
        				}
					    
						$line=array(
								"Staff Member"  => $member['customername'],
            					"Holidays"  => " " . number_format($holidays, 1),
            					"Absences"  => " " . number_format($absences, 1),
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