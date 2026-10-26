<?php 

$connect = odbc_connect("AsahiGlassUpload", "sa","omsai");
 
require_once 'src/PHPMailer.php';
require("src/SMTP.php");
require("src/Exception.php");
 
$Id = $_GET["Id"];

$SqlstmtUF = "SELECT *, CONVERT(varchar, UploadedFileDate, 100) as FileUploadDateTime from Upload_Files as uf 
					join PlantMaster as pm on uf.MachinePlantCode = pm.PlantId 
					inner join LocationMaster as lm on uf.MachineLocationCode = lm.LocationId
					inner join Personal as pr on uf.UploadedBy = pr.PersonName
					inner join MachineMaster as mm on uf.MachineName = mm.MachineName 
					where uf.id='".$Id."'";
$ResultUF = odbc_exec($connect, $SqlstmtUF);
	while ($RowUF = odbc_fetch_array($ResultUF)) {
		$MachineCode = odbc_result($ResultUF, "MachineCode");
		$MachineName = odbc_result($ResultUF, "MachineName");
		$filename1 = odbc_result($ResultUF, "UploadFileName1");
		$filename2 = odbc_result($ResultUF, "UploadFileName2");
		$filename3 = odbc_result($ResultUF, "UploadFileName3");
		$filename4 = odbc_result($ResultUF, "UploadFileName4");
		$filename5 = odbc_result($ResultUF, "UploadFileName5");
		$FileUploadDateTime = odbc_result($ResultUF, "FileUploadDateTime");
		$UploadedBy = odbc_result($ResultUF, "UploadedBy");
		$PlantName = odbc_result($ResultUF, "PlantName");
		$LocationName = odbc_result($ResultUF, "LocationName");
		$Problem_Content = odbc_result($ResultUF, "Problem_Content");
		$Conter_Measure = odbc_result($ResultUF, "Conter_Measure");
		$TOF = odbc_result($ResultUF, "Type_Of_Feedback");
		$RelatedTo = odbc_result($ResultUF, "RelatedTo");
		$UploadId = odbc_result($ResultUF, "UploadId");
		$Id = odbc_result($ResultUF, "Id");
		$Assign_To = odbc_result($ResultUF, "Assign_To");
	}
	
$SqlstmtMail = "Select EMailId, EMailName From EMailGroup Where EMailGroup like '%SH MECH%' OR EMailGroup like '%SH ELEC%'";
$ResultMail = odbc_exec($connect, $SqlstmtMail);
$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); 
//$mail->Host = 'mailrelay.regalbeloit.com';
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
$mail->AddAddress($EMailId, $EMailName);
}
//Set CC address
//$mail->addCC($EMailId, $EMailName);

//$mail->AddAddress('praveen.saxena2407@gmail.com', 'Praveen Saxena');
//$mail->AddAddress('Ajay.Dhiman@aisglass.com', 'Ajay Dhiman');
$mail->Subject = 'FMS ID - '.$UploadId.' FMS IS REOPEN';
$mail->IsHTML(true);
$htmlContent = ' 
    <html>
    <body> 
        <h3 style="color:#000;">Dear '.$Assign_To.',</h3> 
        <h3 style="color:#000;">FMS Details Below::</h3> 
        <h3 style="color:#000;">For Reopen details please contact Uploaded By '.$UploadedBy.' </h3> 
        <table border="0" id="statustable" style="font-size: 10pt; width:100%; font-family: Verdana" cellspacing="0">
			<tr style="background-color: #ee9254; text-align:center; color:#000; font-weight:bold;">
				<td> UPLOAD DATE FMS ID </td>
				<td> MACHINE NAME/CODE </td>
				<td style="width:400px;"> FEEDBACK / RELATED TO </td>
				<td style="width:400px;"> DESCRIPTION </td>
				<td> PLANT <br />ATTACHMENTS </td>
				<td> UPLOADED BY </td>
				<td> ASSIGN TO </td>
			</tr> 
			<tr style="background-color: #fbe4d5; text-align:center; vertical-align: top;">
				<td> '.$FileUploadDateTime.' <br /><br /><font style="color:red"> '.$UploadId.' </td>
				<td> '.$MachineName.' <br /><br /><font style="color:red"> '.$MachineCode.' </td>
				<td> 
					<table style="width:100%;">
						<tr>
							<td style="color:red">'.$TOF.' <br /><br /></td>
							<td style="color:red">'.$RelatedTo.' <br /><br /></td>
						</tr>
						<tr>
							<td colspan="2" style="color:#000;">'.$Problem_Content.'</td>
						</tr>
					</table>
				</td>
				<td>'.$Conter_Measure.'</td>
				<td style="color:#0000ff;">'.$filename1.'<br />'.$filename2.'<br />'.$filename3.'<br />'.$filename4.'<br />'.$filename5.'<br /></td>
				<td>'.$UploadedBy.'</td>
				<td>'.$Assign_To.'</td>
			</tr> 
		</table>
		<table style="background-color: #fff;">
			<tr>
				<td style="font-size:10pt;">Review it By LOGIN in  <a href="http://70.0.0.60/asahi_glass_upload/" >FMS Portal </a> </td>
			</tr> <br />
			<tr>
				<td style="font-size:10pt; style="font-style: italic;">
					<font style="font-weight: bold; font-size: 20px;"> • </font>
					If required please add Drawing number/Document name, Location on server/EPDM in the closing description.
				</td>
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