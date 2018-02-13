<?php
include_once('../PHPMailer-master/PHPMailerAutoload.php');

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host         = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth     = true;                               // Enable SMTP authentication
$mail->Username     = 'devgun.booking@gmail.com';                 // SMTP username
$mail->Password     = 'password.';                           // SMTP password
$mail->SMTPSecure   = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port         = 465 ;                                    // TCP port to connect to

$mail->CharSet = "utf-8";


$mail->setFrom('devgun.booking@gmail.com', 'Booking');
$mail->addAddress('surapong.kawkangploo@gmail.com', 'surapong kawkangploo');     // Add a recipient
/*
$mail->addReplyTo('surapong.kawkangploo@gmail.com', 'surapong kawkangploo');
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');*/

$mail->isHTML(true);                                  // Set email format to HTML
$img = '../img/1488461326107.jpg';
$mail->AddAttachment($img);


$htmlContent = file_get_contents("template.html");

$mail->Subject = 'แจ้งการจอง';
$mail->Body    = $htmlContent;
$mail->AltBody = '';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}










?>