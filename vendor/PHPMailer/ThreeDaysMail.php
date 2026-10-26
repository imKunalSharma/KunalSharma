<?php
//$connect = odbc_connect("AsahiGlassUpload", "sa","server+123");
$connect = odbc_connect("AsahiGlassUpload", "sa","omsai");

    //header("Refresh: 1");

echo !extension_loaded('openssl')?"Not Available":"Available";
echo"<br>";
//Variables {
date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
$Current_date=date('d-m-Y');
print "Date : ".$Current_date."<br>";
$Date="01-03-2021";
print "Date : ".$Date."<br>";
$current_time=date('h:i:s a');
$time="10:00:10 am";
$newtime=date('d-m-y,h:i:s a',strtotime('+2 hour',strtotime($time)));
print"Current time : ".$current_time."<br>";
print"Time : ".$time."<br>";
print"New Time : ".$newtime."<br>";
$diff = abs(strtotime($Date) - strtotime($Current_date));
print "Diff : ".$diff."<br>";
$years = floor($diff / (365*60*60*24));  
print "Years : ".$years."<br>";
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
print "Month : ".$months."<br>";
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 

print "Days : ".$days."<br>";
$d=$days%3;
print"D : ".$d."<br>";
// end of variable  }
// Import PHPMailer classes into the global namespace 
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Execption;
//Include PHPMailer library files
require 'src/Exception.php'; 
require 'src/PHPMailer.php'; 
require 'src/SMTP.php'; 

$Assign_To_Array=array();
$Join_Id_Array=array();
	$SqlStmtAssignee = "Select Distinct Assign_To from Upload_Files where Assign_To IS NOT NULL";
	//$SqlStmtAssignee = "Select Distinct Assign_To from Upload_Files";
	$ResultAssignee = odbc_exec($connect, $SqlStmtAssignee);
	while ($RowAssignee = odbc_fetch_array($ResultAssignee)) {
		$Assign_To = odbc_result($ResultAssignee, "Assign_To");
		//$Counter = $Counter + 1;
		array_push($Assign_To_Array,$Assign_To);
	}
	$Assign_To_Array_Length = count($Assign_To_Array);
	echo "Total Count : ". $Assign_To_Array_Length."<br />";
//print_r($Assign_To_Array);

for($ASS_To=0; $ASS_To<$Assign_To_Array_Length ; $ASS_To++){
	$Assign_To_PersonName = $Assign_To_Array[$ASS_To];
	echo "Assign_To_PersonName : ". $Assign_To_PersonName;
	print $Sqlstmt_Total_Assign_To = "Select Count(*) Total_Assign_To from Upload_Files where Assign_To IS NOT NULL AND Assign_To = '".$Assign_To_PersonName."'";
	print $Sqlstmt_Total_Assign_To."<br />";
	$Result_Total_Assign_To = odbc_exec($connect, $Sqlstmt_Total_Assign_To);
	while ($Row_Total_Assign_To = odbc_fetch_array($Result_Total_Assign_To)) {
		$Total_Assign_To = odbc_result($Result_Total_Assign_To, "Total_Assign_To");	
	}
	$Sqlstmt_Assign_To_Id = "Select Id from Upload_Files where Assign_To IS NOT NULL AND Assign_To = '".$Assign_To_PersonName."'";
	print $Sqlstmt_Assign_To_Id."<br />";
	$Result_Assign_To_Id = odbc_exec($connect, $Sqlstmt_Assign_To_Id);
	while ($Row_Assign_To_Id = odbc_fetch_array($Result_Assign_To_Id)) {
		$Id = odbc_result($Result_Assign_To_Id, "Id");
		//print "Id : ".$Id."<br />";
		//$Joint_Id = implode(",",$Id);
		$Joint_Id_Array = array_push($Join_Id_Array,$Id);
		//$Joint_Id_Array=explode(',', $Id);
		//print_r($Join_Id_Array);
	}
		$Joint_Id = implode(",",$Join_Id_Array);	
	print "Joint_Id : ".$Joint_Id."<br />";		
		$SqlstmtMail = "Select EMailId, PersonName From Personal Where PersonName = '".$Assign_To_PersonName."'";
		print $SqlstmtMail."<br />";
		$ResultMail = odbc_exec($connect, $SqlstmtMail);

		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->IsSMTP(); $mail->IsSMTP(); 
//$mail->Host = '80.0.0.121';
$mail->Host = 'mail.softechcontrolsolutions.com';
$mail->Port = 25;
$mail->SMTPAuth = true;
$mail->Username = 'praveen@softechcontrolsolutions.com';
$mail->Password = 'Praveen@123';
$mail->SetFrom('praveen@softechcontrolsolutions.com', 'Softech Control Solutions');
		while ($RowMail = odbc_fetch_array($ResultMail)) {
			$EMailId = odbc_result($ResultMail, "EMailId");
			$PersonName = odbc_result($ResultMail, "PersonName");
			//print "EMailId : ".$EMailId."<br />";
		$mail->AddAddress($EMailId, $PersonName);
		}
		$mail->AddAddress('praveen.saxena2407@gmail.com', 'Praveen Kumar');
		$mail->Subject='PENDING FMS-REQUIRED YOUR ATTENTION';

		//Set email format to HTML
		$mail->isHTML(true);
		// Email body content 
		$mailContent = ' 
			<div> <h4>Dear '.$Assign_To_PersonName.',</h4> </div>
			<div> <h4>Pending FMS for you :: <font style="color:red;">'.$Total_Assign_To.'</font></h4> </div>       
			<div> <h4>Your Id\'s Are :: <font style="color:green;">'.$Joint_Id.'</font></h4> </div>       
			<div> Close it By LOGIN in  <a href="http://70.0.0.60/asahi_glass_upload/" >FMS Portal </a> </div><br /> 
			<div> <i>If required please add Drawing number/Document name, Location on server/EPDM in the closing description. </i></div><br /> 
			<div style="color:red; font-size: 12pt; font-weight:bold;"> :: Please Do not reply on this mail. </div>
			
			<div> Thanks, <br /> 
				<font style="color:#000;font-weight:bold;">FMS </font> <br /> 
				<font style="color:#000;font-weight:bold;">R&D Portal (AIS) </font>
			</div>
		'; 
		$mail->Body = $mailContent; 
		 
		// Send email 
		if(!$mail->send()){ 
			echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
		}else{ 
			
			echo 'Message has been sent.'; 
		}
}
echo "Total_Assign_To : ". $Total_Assign_To."<br />";
$Counter = 0;
if($d==0){
	print "First Condition days==0 is true !!!";
if($current_time==$time){
	print "First Condition current_time==time is true !!!";
	
	
}
else{
	echo"time is over"; 
}
}
else{
	echo"sorry";
}

exit ();
?>
 