<?php
include('smtp/PHPMailerAutoload.php');
function smtp_mailer($to, $subject, $msg) //calling function for creating mail
{
    $mail = new PHPMailer(); //mail initiates
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";  //host address
    $mail->Port = 587; //common mail port
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    //$mail->SMTPDebug = 2; 
    $mail->Username = "serenitystyles.online@gmail.com"; // Sender's Email
    $mail->Password = "jvyaqzlqqwjhywdu"; // Sender's Email App Password
    $mail->SetFrom("serenitystyles.online@gmail.com"); // Sender's Email
    $mail->Subject = $subject; //subject of sending mail 
    $mail->Body = $msg; //body of sending mail
    $mail->AddAddress($to);  //to address of sending mail
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if (!$mail->Send()) {   //
        return $mail->ErrorInfo;
    } else {
        return "Reset link has been sent to your email address: " . $to;
    }
}
?>