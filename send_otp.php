

   
<?php
include('smtp/PHPMailerAutoload.php');


function smtp_mailer($to) //calling function for creating mail
{
    include('connect.php');
    $email = $to;
    
    // Generate OTP
    $otp = rand(100000, 999999);
    
    // Save OTP to database
    $stmt = $conn->prepare("INSERT INTO tbl_verification (email, otp) VALUES (?, ?) ON DUPLICATE KEY UPDATE otp=?");
    $stmt->bind_param("sss", $email, $otp, $otp);
    $stmt->execute();
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
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is <strong>" . $otp . "</strong>. It will expire in 10 minutes.";


    $mail->AddAddress($to);  //to address of sending mail
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if (!$mail->Send()) {   //
        return $mail->ErrorInfo;
    } else {
        return "OTP sent to your email:"  . $to;
    }
}
$conn->close();
session_abort();
?>