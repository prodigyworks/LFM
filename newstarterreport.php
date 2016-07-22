<?php
	require_once('system-db.php');
	require_once('pdfreport.php');
	require_once("simple_html_dom.php");
	
	class NewStarterReport extends PDFReport {
		
		function AddPage($orientation='', $size='') {
			parent::AddPage($orientation, $size);
			
			$this->addText( 10, 13, "Trianik Cleaners Ltd Starter Form", 12, 4, 'B');
						
			$this->Image("images/logomain2.png", 178.6, 1);
			$this->addText( 40, 288, "Trianik Cleaners Ltd - Starter Forms revised August 2015 - Mrs N M Saunders - Managing Director TCL", 8, 4, '');
			$this->Rect(5, 21, 200, 259);
			$this->Line(48, 21, 48, 279.9);
		}
		
		function __construct($orientation, $metric, $size, $id) {
			$dynamicY = 0;
			
	        parent::__construct($orientation, $metric, $size);
	        
	        $this->SetAutoPageBreak(true, 5);
	        
			$this->AddPage();
			
			try {
				$sql = "SELECT *, DATE_FORMAT(A.startdate, '%d/%m/%Y') AS startdate
						FROM {$_SESSION['DB_PREFIX']}client A 
						WHERE id = $id";
				$result = mysql_query($sql);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {

						$this->SetFillColor(100, 255, 100);
						$this->Rect(5.3, 21.3, 42.6, 7, "F");
						$this->Rect(86.3, 21.3, 31.6, 6.5, "F");
						$this->Rect(148.3, 21.3, 22.6, 7, "F");
						$this->Rect(5.3, 55.3, 42.6, 25.4, "F");
						$this->Rect(5.3, 88.3, 42.6, 7, "F");
						$this->Rect(5.3, 105.3, 42.6, 10.6, "F");
						$this->Rect(5.3, 123.5, 42.6, 60.6, "F");
						$this->Rect(5.3, 206.5, 42.6, 51.6, "F");
						
						$this->SetFillColor(150, 150, 255);
						$this->Rect(5.3, 28.3, 42.6, 26.5, "F");
						$this->Rect(5.3, 81.4, 42.6, 7, "F");
						$this->Rect(5.3, 95.4, 42.6, 10, "F");
						$this->Rect(106.3, 81.4, 31.6, 6.5, "F");
						$this->Rect(5.3, 116.4, 42.6, 7, "F");
						$this->Rect(5.3, 184.4, 42.6, 22, "F");
						$this->Rect(5.3, 258.4, 42.6, 21.2, "F");
						
						$this->Line(86, 21, 86, 28);
						$this->Line(118, 21, 118, 28);
						$this->Line(148, 21, 148, 28);
						$this->Line(171, 21, 171, 28);
						$this->Line(106, 81, 106, 88);
						$this->Line(138, 81, 138, 88);
						
						$this->SetFillColor(0, 0, 0);
						$this->Rect(50, 128.4, 82.6, 7);
						$this->Rect(50, 140.4, 144, 7);
						$this->Rect(50, 152.6, 144, 7);
						$this->Line(59, 152.6, 59, 159.6);
						$this->Line(68, 152.6, 68, 159.6);
						$this->Line(77, 152.6, 77, 159.6);
						$this->Line(86, 152.6, 86, 159.6);
						$this->Line(95, 152.6, 95, 159.6);
						$this->Line(104, 152.6, 104, 159.6);
						$this->Line(113, 152.6, 113, 159.6);
						$this->Line(122, 152.6, 122, 159.6);
						$this->Line(131, 152.6, 131, 159.6);
						$this->Line(140, 152.6, 140, 159.6);
						$this->Line(149, 152.6, 149, 159.6);
						$this->Line(158, 152.6, 158, 159.6);
						$this->Line(167, 152.6, 167, 159.6);
						$this->Line(176, 152.6, 176, 159.6);
						$this->Line(185, 152.6, 185, 159.6);
						
						$this->Rect(50, 164.6, 18, 7);
						$this->Line(59, 164.6, 59, 171.6);
						$this->Rect(80, 164.6, 18, 7);
						$this->Line(89, 164.6, 89, 171.6);
						$this->Rect(110, 164.6, 27, 7);
						$this->Line(119, 164.6, 119, 171.6);
						$this->Line(128, 164.6, 128, 171.6);
						$this->Rect(150, 164.6, 21, 7);
						
						$this->Rect(50, 191.6, 136.5, 9);
						$this->Line(117.5, 200.6, 117.5, 206);
						$this->Line(126.5, 200.6, 126.5, 206);
						$this->Line(136.5, 200.6, 136.5, 206);
						$this->Line(146.5, 200.6, 146.5, 206);
						$this->Line(156.5, 200.6, 156.5, 206);
						$this->Line(166.5, 200.6, 166.5, 206);
						$this->Line(176.5, 200.6, 176.5, 206);
						$this->Line(186.5, 200.6, 186.5, 206);
						
						$this->Rect(92.5, 216, 10, 6);
						$this->Rect(92.5, 224, 10, 6);
						$this->Rect(92.5, 232, 50, 6);
						$this->Rect(92.5, 240, 100, 6);
						$this->Line(102.5, 232, 102.5, 238);
						$this->Line(112.5, 232, 112.5, 238);
						$this->Line(122.5, 232, 122.5, 238);
						$this->Line(132.5, 232, 132.5, 238);
						
						$margin = 74;
						$this->Rect(50, 191.6 + $margin, 136.5, 9);
						$this->Line(117.5, 200.6 + $margin, 117.5, 206 + $margin);
						$this->Line(126.5, 200.6 + $margin, 126.5, 206 + $margin);
						$this->Line(136.5, 200.6 + $margin, 136.5, 206 + $margin);
						$this->Line(146.5, 200.6 + $margin, 146.5, 206 + $margin);
						$this->Line(156.5, 200.6 + $margin, 156.5, 206 + $margin);
						$this->Line(166.5, 200.6 + $margin, 166.5, 206 + $margin);
						$this->Line(176.5, 200.6 + $margin, 176.5, 206 + $margin);
						$this->Line(186.5, 200.6 + $margin, 186.5, 206 + $margin);
						
						$this->Line(5, 28, 205, 28);
						$this->Line(5, 55, 205, 55);
						$this->Line(5, 81, 205, 81);
						$this->Line(5, 88, 205, 88);
						$this->Line(5, 95.5, 205, 95.5);
						$this->Line(5, 105, 205, 105);
						$this->Line(5, 116, 205, 116);
						$this->Line(5, 123.5, 205, 123.5);
						$this->Line(5, 184.5, 205, 184.5);
						$this->Line(5, 206.2, 205, 206.2);
						$this->Line(5, 258.2, 205, 258.2);
						
//						if ($member['paymentmethod'] == "Q") {
//							$paymentmethod = "Cheque";
//						
//						} else if ($member['paymentmethod'] == "C") {
//							$paymentmethod = "Credit Card";
//						
//						} else if ($member['paymentmethod'] == "D") {
//							$paymentmethod = "Debit Card";
//						
//						} else if ($member['paymentmethod'] == "B") {
//							$paymentmethod = "Bank Transfer";
//						}
						$paymentmethod = "Cheque / Credit Card / Debit Card / Bank Transfer";
						
						$this->addText( 10, 23, "Name of Organisation", 10, 4, '');
						$this->addText( 50, 23, $member['name'], 10, 4, '');
						$this->addText( 90, 23, "Contact Name", 10, 4, '');
						$this->addText( 120, 23, $member['firstname'] . " " . $member['lastname'], 10, 4, '');
						$this->addText( 150, 23, "Key Codes", 10, 4, '');
						$this->addText( 173, 23, $member['keycodes'], 10, 4, '');
						
						$this->addText( 10, 38, "Address", 10, 4, '');
						$this->addText( 50, 30, $member['address'], 10, 4, '');
						
						$this->addText( 10, 63, "Billing Address If Different", 10, 4, '', 29);
						$this->addText( 50, 57, $member['billingaddress'], 10, 4, '');
						
						$this->addText( 10, 83, "Home Phone", 10, 4, '', 30);
						$this->addText( 50, 83, $member['telephone'], 10, 4, '', 30);
						
						$this->addText( 110, 83, "Mobile number", 10, 4, '');
						$this->addText( 140, 83, $member['mobile'], 10, 4, '', 30);
						
						$this->addText( 10, 90, "Work Phone", 10, 4, '', 30);
						$this->addText( 50, 90, $member['workphone'], 10, 4, '', 30);
						
						$this->addText( 10, 98, "Email Address", 10, 4, '', 30);
						$this->addText( 50, 98, $member['email'], 10, 4, '');
						
						$this->addText( 10, 107, "Paper Free Biling Address", 10, 4, '', 30);
						$this->addText( 50, 108.5, $member['paperfreebillingaddress'], 10, 4, '');
						
						$this->addText( 10, 118, "Payment Method", 10, 4, '', 30);
						$this->addText( 50, 118, $paymentmethod, 10, 4, '');
						$this->addText( 10, 152, "Credit/Debit Card Details", 10, 4, '', 30);
						
						$this->addText( 50, 125, "Name as it appears on Card", 8, 3, 'B');
						$this->addText( 50, 137, "Address of cardholder", 8, 3, 'B');
						$this->addText( 50, 149, "16 Digits on the front of the card", 8, 3, 'B');
						$this->addText( 50, 161, "Valid from", 8, 3, 'B');
						$this->addText( 80, 161, "Expiry Date", 8, 3, 'B');
						$this->addText( 110, 161, "Card security code", 8, 3, 'B');
						$this->addText( 150, 161, "Issue Number", 8, 3, 'B');
						
						$this->SetTextColor(180, 180, 180);
						$this->addText( 51, 167, "MM", 8, 3, '');
						$this->addText( 60.4, 167, "YY", 8, 3, '');
						
						$this->addText( 81, 167, "MM", 8, 3, '');
						$this->addText( 90, 167, "YY", 8, 3, '');
						
						$this->SetTextColor(0, 0, 0);
						
						$this->Image("images/newstarterform.png", 50, 173);
						
						$this->addText( 10, 192, "Payment Authorisation", 10, 4, '', 30);
						$this->addText( 50, 186, "I authorise Trianik Cleaners Ltd to debit my account monthly on or around the _________ of the Month", 8, 3, '');
						$this->addText( 50, 196, "Signature", 8, 3, 'B');
						
						$this->SetTextColor(255, 0, 0);
						$this->addText( 66, 194, "X", 16, 5, 'B');
						
						$this->SetTextColor(0, 0, 0);
						
						$this->addText( 118, 196, "Print Name", 8, 3, 'B');
						$this->addText( 118, 202, "Date", 8, 3, 'B');
						
						$this->SetTextColor(180, 180, 180);
						$this->addText( 130, 202, "D", 8, 3, '');
						$this->addText( 140, 202, "D", 8, 3, '');
						$this->addText( 150, 202, "M", 8, 3, '');
						$this->addText( 160, 202, "M", 8, 3, '');
						$this->addText( 170, 202, "Y", 8, 3, '');
						$this->addText( 180, 202, "Y", 8, 3, '');
						
						$this->SetTextColor(0, 0, 0);

						$this->addText( 50, 208, "TCL will debit your card for payment on or after the date shown above. Please make sure you have enough money in your account to meet the payment in full", 8, 3, 'B', 146);

						$this->addText( 10, 232, "Keyholding", 10, 4, '', 30);
						$this->addText( 50, 218, "Number of keys given ?", 8, 3, 'B');
						$this->addText( 93, 218, $member['numberofkeysgiven'], 8, 3, 'B');
						
						$this->addText( 50, 226, "Alarm Fob been given ?", 8, 3, 'B');
						$this->addText( 93, 226, $member['alarmfobgiven'] == "Y" ? "Yes" : "No", 8, 3, 'B');
						
						$this->addText( 50, 234, "Alarm Codes", 8, 3, 'B');
						
						if (strlen($member['alarmcodes']) >= 1) {
							$this->addText( 95, 234, substr($member['alarmcodes'], 0, 1), 8, 3, 'B');
						}
						
						if (strlen($member['alarmcodes']) >= 2) {
							$this->addText( 105, 234, substr($member['alarmcodes'], 1, 1), 8, 3, 'B');
						}
						
						if (strlen($member['alarmcodes']) >= 3) {
							$this->addText( 115, 234, substr($member['alarmcodes'], 2, 1), 8, 3, 'B');
						}
												
						if (strlen($member['alarmcodes']) >= 4) {
							$this->addText( 125, 234, substr($member['alarmcodes'], 3, 1), 8, 3, 'B');
						}
												
						if (strlen($member['alarmcodes']) >= 5) {
							$this->addText( 135, 234, substr($member['alarmcodes'], 4, 1), 8, 3, 'B');
						}
						
						$this->addText( 50, 242, "Access Instructions", 8, 3, 'B');
						$this->addText( 93, 242, $member['accessinstructions'], 8, 3, 'B');
						
						$this->addText( 50, 250, "Trianik take responsibility of your keys, they will be given a keyring with its own unique code, never displaying names or address, we will only gain access to your property at mutually agreed times.", 8, 3, 'B', 146);
						
						$this->addText( 10, 270, "Access Authorisation", 10, 4, '', 30);
						$this->addText( 50, 260, "I authorise Trianik Cleaners Ltd to act as resposible key holders to my property.", 8, 3, 'B', 146);
						
						$this->addText( 50, 270, "Signature", 8, 3, 'B');
						
						$this->SetTextColor(255, 0, 0);
						$this->addText( 66, 268, "X", 16, 5, 'B');
						
						$this->SetTextColor(0, 0, 0);
						
						$this->addText( 118, 270, "Print Name", 8, 3, 'B');
						$this->addText( 118, 276, "Date", 8, 3, 'B');
						
						$this->SetTextColor(180, 180, 180);
						$this->addText( 130, 276, "D", 8, 3, '');
						$this->addText( 140, 276, "D", 8, 3, '');
						$this->addText( 150, 276, "M", 8, 3, '');
						$this->addText( 160, 276, "M", 8, 3, '');
						$this->addText( 170, 276, "Y", 8, 3, '');
						$this->addText( 180, 276, "Y", 8, 3, '');
						
						$this->SetTextColor(0, 0, 0);
						
						/************************ Page 2 *********************/
						
						$this->AddPage();
						
						$this->SetFillColor(100, 255, 100);
						$this->Rect(5.3, 21.3, 42.6, 6.5, "F");
						$this->Rect(106.3, 21.3, 41.6, 6.5, "F");
						$this->Rect(5.3, 36.2, 42.6, 7.5, "F");
						$this->Rect(106.3, 36.2, 41.6, 7.5, "F");
						$this->Rect(5.3, 58.2, 42.6, 7.5, "F");
						$this->Rect(5.3, 73.3, 42.6, 206.4, "F");
						
						$this->SetFillColor(150, 150, 255);
						$this->Rect(5.3, 28.3, 42.6, 7.2, "F");
						$this->Rect(106.3, 28.4, 41.6, 7.2, "F");
						$this->Rect(5.3, 44.3, 42.6, 13.7, "F");
						$this->Rect(5.3, 65.8, 42.6, 7.2, "F");
						
						$this->Line(5, 28, 205, 28);
						$this->Line(5, 36, 205, 36);
						$this->Line(5, 44, 205, 44);
						$this->Line(5, 58, 205, 58);
						$this->Line(5, 66, 205, 66);
						$this->Line(5, 73, 205, 73);
						$this->Line(106.3, 20.9, 106.3, 44);
						$this->Line(147.9, 20.9, 147.9, 44);
						
						if ($member['frequency'] == "O") {
							$frequency = "One Off";
							
						} else if ($member['frequency'] == "W") {
							$frequency = "Weekly";
							
						} else if ($member['frequency'] == "T") {
							$frequency = "Two Weekly";
							
						} else if ($member['frequency'] == "F") {
							$frequency = "Four Weekly";

						} else {
							$frequency = "";
						}
						
						$this->addText( 10, 23, "Frequency of cleaning", 10, 4, '');
						$this->addText( 50, 23, $frequency, 8, 3, '');
						
						$this->addText( 110, 23, "Start Date", 10, 4, '');
						$this->addText( 150, 23, $member['startdate'] == "00/00/0000" ? "N/A" : $member['startdate'], 8, 3, '');
						
						$this->addText( 10, 31, "Hours Required", 10, 4, '');
						$this->addText( 50, 31, $member['hoursrequired'], 8, 3, '');
						
						$this->addText( 110, 31, "Preferred day/time", 10, 4, '');
						$this->addText( 150, 31, $member['preferreddaytime'], 8, 3, '');
						
						$this->addText( 10, 38, "Number of Bedrooms", 10, 4, '');
						$this->addText( 50, 38, $member['numberofbedrooms'], 8, 3, '');
						
						$this->addText( 110, 38, "Number of Bathrooms", 10, 4, '');
						$this->addText( 150, 38, $member['numberofbathrooms'], 8, 3, '');
						
						$this->addText( 10, 45, "Valuable items to be removed or not cleaned", 10, 4, '', 34);
						$this->addText( 50, 45, $member['valuablestoberemoved'], 8, 3, '');
						
						$this->addText( 10, 60, "Vacuum", 10, 4, '', 30);
						$this->addText( 50, 60, $member['vacuum'] == "Y" ? "Yes" : "No", 8, 3, '');
						
						$this->addText( 10, 67, "Ironing required", 10, 4, '', 30);
						$this->addText( 50, 67, $member['ironingrequired'] == "Y" ? "Yes" : "No", 8, 3, '');
												
						$this->addText( 10, 74, "Specific Cleaning Requirements", 10, 4, '', 32);
						$this->addText( 50, 74, $member['specificrequirements'], 8, 3, '', 153);
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
	
	$pdf = new NewStarterReport( 'P', 'mm', 'A4', $_GET['id']);
	$pdf->Output();
?>