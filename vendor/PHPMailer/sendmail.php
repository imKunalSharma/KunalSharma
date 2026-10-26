<?php 

$connect = odbc_connect("AsahiGlassUpload", "sa","omsai");
 
require_once 'src/PHPMailer.php';
require("src/SMTP.php");
require("src/Exception.php");
 
$Problem_Content = $_GET["Problem_Content"];
$Conter_Measure = $_GET["Conter_Measure"];
$UploadedFileDate = $_GET["todaydate"];
$UploadId = $_GET["UploadId"];
$machinename = $_GET["machinename"];
$TOF = $_GET["TOF"];
$Relatedto = $_GET["Relatedto"];
$Plant = $_GET["Plant"];
$Location = $_GET["Location"];
$UploadedBy = $_GET["UploadedBy"];
$filename1 = $_GET["filename1"];
$filename2 = $_GET["filename2"];
$filename3 = $_GET["filename3"];
$filename4 = $_GET["filename4"];
$filename5 = $_GET["filename5"];
print "Problem_Content : ".$Problem_Content."<br />";

$SqlstmtMch = "Select MachineCode From MachineMaster where MachineName = '".$machinename."'";
$ResultMch = odbc_exec($connect, $SqlstmtMch);
	while ($RowMch = odbc_fetch_array($ResultMch)) {
		$MachineCode = odbc_result($ResultMch, "MachineCode");
	}
	
$SqlstmtMail = "Select EMailId, EMailName From EMailGroup Where EMailGroup like '%SH MECH%' OR EMailGroup like '%SH ELEC%'";
$ResultMail = odbc_exec($connect, $SqlstmtMail);
while ($RowMail = odbc_fetch_array($ResultMail)) {
	$EMailId = odbc_result($ResultMail, "EMailId");
	$EMailName = odbc_result($ResultMail, "EMailName");
	print "EMailId : ".$EMailId."<br />";
$mail->AddAddress($EMailId, $EMailName);
}
$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); 
$mail->Host = 'mail.softechcontrolsolutions.com';
$mail->Port = 25;
$mail->SMTPAuth = true;
$mail->Username = 'praveen@softechcontrolsolutions.com';
$mail->Password = 'Praveen@123';
$mail->SetFrom('praveen@softechcontrolsolutions.com', 'Softech Control Solutions');

//Set CC address
//$mail->addCC("ccaddress@ccdomain.com", "Some CC Name");

$mail->Subject = 'NEW FMS GENERATED  ID - '.$UploadId;
$mail->IsHTML(true);
$htmlContent = ' 
    <html>
    <body> 
        <h3 style="color:#000;">Dear Team,</h3> 
        <h3 style="color:#000;">FMS Details Below::</h3> 
        <table border="0" id="statustable" style="font-size: 10pt; width:100%; font-family: Verdana" cellspacing="0">
			<tr style="background-color: #70ad47; text-align:center; color:#000; font-weight:bold;">
				<td> UPLOAD DATE FMS ID </td>
				<td> MACHINE NAME/CODE </td>
				<td style="width:400px;"> FEEDBACK / RELATED TO </td>
				<td style="width:400px;"> DESCRIPTION </td>
				<td> PLANT <br />ATTACHMENTS </td>
				<td> UPLOADED BY </td>
				<td> ASSIGN TO </td>
			</tr> 
			<tr style="background-color: #e2efd9; text-align:center; vertical-align: top;">
				<td> '.$UploadedFileDate.' <br /><br /><font style="color:red"> '.$UploadId.' </td>
				<td> '.$machinename.' <br /><br /><font style="color:red"> '.$MachineCode.' </td>
				<td> 
					<table style="width:100%;">
						<tr>
							<td style="color:red">'.$TOF.' <br /><br /></td>
							<td style="color:red">'.$Relatedto.' <br /><br /></td>
						</tr>
						<tr>
							<td colspan="2" style="color:#000;">'.$Problem_Content.'</td>
						</tr>
					</table>
				</td>
				<td>'.$Conter_Measure.'</td>
				<td style="color:#0000ff;">'.$filename1.'<br />'.$filename2.'<br />'.$filename3.'<br />'.$filename4.'<br />'.$filename5.'<br /></td>
				<td>'.$UploadedBy.'</td>
				<td>None</td>
			</tr> 
		</table>
		<table style="background-color: #fff;">
			<tr>
				<td style="font-size:10pt;">Assign it By LOGIN in <a href="http://70.0.0.60/asahi_glass_upload/" >FMS Portal </a> </td>
			</tr> <br />
			<tr>
				<td style="background-color: yellow; color:red; font-size: 12pt; font-weight:bold;">:: Please Do not reply on this mail. </td> 
			</tr><br /> <br /> <br />
			<tr>
				<td style="color:#000;">Thanks, <br /> 
					<font style="color:#000;font-weight:bold;">FMS </font> <br /> 
					<font style="color:#000;font-weight:bold;">R&D Portal (AIS) </font>
				</td>
			</tr>
		</table>
		
    </body> 
    </html>'; 
	//$mail->Body = 'This e-mail Has been sent through Softech with SMTP Server.';
	$mail->Body = $htmlContent;
	//$mail->AddAttachment('path/logo.png', 'logo.png');
	if(!$mail->Send()) {
	  echo 'Mailer error: '.$mail->ErrorInfo;
	} else {
	  echo 'Message has been sent.';
	}
	
	//mailrelay.regalbeloit.com  

//port - 25
?>