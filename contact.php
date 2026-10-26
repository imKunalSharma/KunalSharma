<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Replace contact@example.com with your real receiving email address
  /*
  $receiving_email_address = 'ks66sharma@gmail.com';

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];

  // Uncomment below code if you want to use SMTP to send emails. You need to enter your correct SMTP credentials
 

  $contact->add_message( $_POST['name'], 'From');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['message'], 'Message', 10);

  echo "asdf".$contact->send();
  */
  
  
  
  
require_once 'PHPMailer/src/PHPMailer.php';
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");
  
$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); 
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
//$mail->Port = 2521;
$mail->SMTPAuth = true;
$mail->Username = 'ks66sharma@gmail.com';
$mail->Password = 'dtdq jnwr rvsk asid';
$mail->SetFrom('ks66sharma@gmail.com', 'Softech Control Solutions ');
$mail->AddAddress('kunal66sharma@gmail.com', 'kunal sharma');
$mail->Subject = 'PMS Production Report ';
$mail->IsHTML(true);
$htmlContent = ' Hello sir';

mail->Body = $htmlContent;
//$mail->AddAttachment('path/logo.png', 'logo.png');
if(!$mail->Send()) {
  echo 'Mailer error: '.$mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}  

  
?>
