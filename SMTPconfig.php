<?php
//Server Address
//include "vars.php";
$dbcon = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
global $site_id;
include('class.phpmailer.php'); 
$mail = new PHPMailer(true);

//echo "SELECT * FROM orders_configuration where site_id=$site_id";die;
$sqlSMTP = @mysqli_query($dbcon,"SELECT * FROM orders_configuration where site_id=$site_id");
$rowSMTP = @mysqli_fetch_array($sqlSMTP);
$mail->isSMTP(); // Set mailer to use SMTP
$mail->Mailer = "smtp";
$mail->Host = '199.192.21.152'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );
$mail->Host = 'localhost';
$mail->SMTPAuth = false;
$mail->SMTPAutoTLS = false; 
$mail->Username = 'info@admin1.bestessaywriters.com'; // SMTP username
$mail->Password = 'bH+p;eWTJ.Ni'; // SMTP password
//$mail->SMTPSecure = 'tls'; // Enable TLS encryption, [ICODE]ssl[/ICODE] also accepted
$mail->Port = 25; // TCP port to connect to 
$mail->From="info@admin1.bestessaywriters.com";
$mail->FromName="BestEssayWriter";
 
$SmtpServer=$rowSMTP['smtp_host'];
$SmtpPort=$rowSMTP['smtp_port'];
$SmtpUser=$rowSMTP['smtp_user'];
$SmtpPass=$rowSMTP['smtp_pass'];
$SmtpFrom=$rowSMTP['from_email'];

?>