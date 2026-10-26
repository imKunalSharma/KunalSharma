<?php
//$connect = odbc_connect("AsahiGlassUpload", "sa","server+123");
$connect = odbc_connect("AsahiGlassUpload", "sa","omsai");
   // header("Refresh: 1");

echo !extension_loaded('openssl')?"Not Available":"Available";
echo"<br>";
//Variables {
date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
$Current_date=date('d-m-Y');
print "Date : ".$Current_date."<br>";
$Date="26-02-2021";
print "Date : ".$Date."<br>";
//$current_time=date('h:i:s a');
$current_time=date('h:i a');
//$time="03:34:10 pm";
$time="03:35 pm";

print"Current time : ".$current_time."<br>";
print"Time : ".$time."<br>";

$diff = abs(strtotime($Current_date) - strtotime($Date));
print "Diff : ".$diff."<br>";
$years = floor($diff / (365*60*60*24));  
print "Years : ".$years."<br>";
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
print "Month : ".$months."<br>";
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 

print "Days : ".$days."<br>";
$d=$days%7;
print"D : ".$d."<br>";

    $SqlStmt_fdb = "select Count(*) as Total_Feedback from Upload_Files where Type_Of_Feedback = 'FEEDBACK'";
	$Result_FDB = odbc_exec($connect, $SqlStmt_fdb); 
	while (odbc_fetch_array($Result_FDB)) {
		$Total_Feedback = odbc_result($Result_FDB, "Total_Feedback");
	}
	
    $SqlStmt_mpis = "select Count(*) as Total_MPIS from Upload_Files where Type_Of_Feedback = 'MPIS'";
	$Result_mpis = odbc_exec($connect, $SqlStmt_mpis);
	while (odbc_fetch_array($Result_mpis)) {
		$Total_MPIS = odbc_result($Result_mpis, "Total_MPIS");
	}
	
    $SqlStmt_insp = "select Count(*) as Total_Ispection from Upload_Files where Type_Of_Feedback = 'INSPECTION'";
	$Result_insp = odbc_exec($connect, $SqlStmt_insp);
	while (odbc_fetch_array($Result_insp)) {
		$Total_Ispection = odbc_result($Result_insp, "Total_Ispection");
	}
 
    $SqlStmt_Per = "select PersonName from Personal ";
	$Result_Per = odbc_exec($connect, $SqlStmt_Per);
#print $SqlStmt;

// end of variable  }
// Import PHPMailer classes into the global namespace 
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Execption;
//Include PHPMailer library files

require 'src/Exception.php'; 
require 'src/PHPMailer.php'; 
require 'src/SMTP.php'; 

$SqlstmtMail = "Select EMailId, EMailName From EMailGroup Where EMailGroup like '%SH MECH%' OR EMailGroup like '%SH ELEC%' 
				OR EMailGroup like '%ELEC HEAD%' OR EMailGroup like '%MECH HEAD%'";
				print $SqlstmtMail;
 //PHPMailer object

if($d==0){
//if($current_time==$time){

$ResultMail = odbc_exec($connect, $SqlstmtMail);

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); 
//$mail->Host = '80.0.0.121';
$mail->Host = 'mail.softechcontrolsolutions.com';
$mail->Port = 25;
$mail->SMTPAuth = true;
$mail->Username = 'praveen@softechcontrolsolutions.com';
$mail->Password = 'Praveen@123';
$mail->SetFrom('praveen@softechcontrolsolutions.com', 'Softech Control Solutions');
while ($RowMail = odbc_fetch_array($ResultMail)) {
	$EMailId = odbc_result($ResultMail, "EMailId");
	$EMailName = odbc_result($ResultMail, "EMailName");
	print "EMailId : ".$EMailId."<br />";
//$mail->AddAddress($EMailId, $PersonName);
}
$mail->AddAddress('praveen.saxena2407@gmail.com', 'Praveen Kumar');
$mail->Subject='Weekly Report FMS';

//Set email format to HTML
$mail->isHTML(true);
// Email body content 

$mailContent = " 		
<table width='550px; height:35px;' border=1>
	<tr> 
	<td style='color:red; font-weight:bold;'>TOTAL FEEDBACK</td> 
	<td style='width:30px; text-align:center; color:red; font-weight:bold;'>".$Total_Feedback."</td>  
	<td style='color:blue; font-weight:bold;'>TOTAL MPIS</td> 
	<td style='width:30px; text-align:center; color:blue; font-weight:bold;'>".$Total_MPIS."</td>   
	<td style='color:green; font-weight:bold;'>TOTAL INSPECTION</td> 
	<td style='width:30px; text-align:center; color:green; font-weight:bold;'>".$Total_Ispection."</td> 
	</tr> 
	<tr> 
	<td style='color:red; font-weight:bold;'>TOTAL Design</td> 
	<td style='width:30px; text-align:center; color:red; font-weight:bold;'>".$Total_Feedback."</td>  
	<td style='color:blue; font-weight:bold;'>TOTAL MPIS</td> 
	<td style='width:30px; text-align:center; color:blue; font-weight:bold;'>".$Total_MPIS."</td>   
	<td style='color:green; font-weight:bold;'>TOTAL INSPECTION</td> 
	<td style='width:30px; text-align:center; color:green; font-weight:bold;'>".$Total_Ispection."</td> 
	</tr> 
</table>
<table border='1' id='statustable' style='font-size: 10pt; width:550px; color: #69478c; font-family: Verdana' cellspacing='0'>
	<tr style='color: white; background-color: rgb(105, 71, 140); text-align:center;'> 
	<td style='width:50px;'>SR.NO.</td>
	<td style='width:360px;'>NAME</td>
	<td>FEEDBACK SUBMITTED</td>
	<td>FEEDBACK CLOSED</td>
	<td>FEEDBACK PENDING</td>
	</tr>
		";

while (odbc_fetch_array($Result_Per)) {
	$PersonName = odbc_result($Result_Per, "PersonName");
	$P =1;
    $backcolor = "transparent";
	$Sqlstmt_Open = "Select count(*) as Total_Open From Upload_Files where UploadedBy = '".$PersonName."'";
	$ResultOpen = odbc_exec($connect, $Sqlstmt_Open);
	#print $Sqlstmt_Open."<br />";			
	while (odbc_fetch_array($ResultOpen)) {
		$Total_Open = odbc_result($ResultOpen, "Total_Open");
	}
	
	$Sqlstmt_Close = "Select count(*) as Total_Close From Upload_Files where CompletedBy = '".$PersonName."' and Status = '100'";	
	$ResultClose = odbc_exec($connect, $Sqlstmt_Close);
	#print $Sqlstmt_Close."<br />";			
	while (odbc_fetch_array($ResultClose)) {
		$Total_Close = odbc_result($ResultClose, "Total_Close");
	}
	
	$Sqlstmt_Assign = "Select count(*) as Total_Assign From Upload_Files where Assign_To = '".$PersonName."' and status = '1'";
	$ResultAssign = odbc_exec($connect, $Sqlstmt_Assign);
	#print $Sqlstmt_Assign."<br />";			
	while (odbc_fetch_array($ResultAssign)) {
		$Total_Assign = odbc_result($ResultAssign, "Total_Assign");
	}
		
$mailContent = $mailContent." 
        <tr style=''>        
			<td style='border-bottom: silver thin dashed; background-color: $backcolor;'>".$P."</td>
			<td style='border-bottom: silver thin dashed; background-color: $backcolor;'>".$PersonName."</td>
			<td style='border-bottom: silver thin dashed; background-color: $backcolor;'>".$Total_Open."</td>
			<td style='border-bottom: silver thin dashed; background-color: $backcolor;'>".$Total_Close."</td>
			<td style='border-bottom: silver thin dashed; background-color: $backcolor;'>".$Total_Assign."</td>
        </tr> 
	"; 
         if ($backcolor == "gainsboro") {
             $backcolor = "transparent";
         } else {
             $backcolor = "gainsboro";
         }  
		 $P++;
      }
      
	$mailContent = $mailContent." 
	</table>
   <h4>Dear TEAM,</h4>
<br>
For view Page <a href='http://localhost/PHPMailer/data.php'>Click Here</a>
<br>
<mark style='color:red'><b>:: Please Do Not Reply on this mail.</b></mark>
<p>Thanks,<p>
<h4>FMS<br>R&D Portal (AIS)</h4>
"; 
$mail->Body = $mailContent; 
 
// Send email 
if(!$mail->send()){ 
    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
}else{ 
    
	echo 'Message has been sent.'; 
}

//}	
}
else{
	echo"time is over"; 
}

exit ();
?>
 