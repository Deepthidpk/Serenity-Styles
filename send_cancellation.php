<?php
include('smtp/PHPMailerAutoload.php');

function smtp_cancellation_mailer($to, $time, $date, $service_name) // Calling function for rejection mail
{
    $reason="Shop maintenance issue";
    include('connect.php');
    
    $mail = new PHPMailer(); // Mail initiates
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";  // Host address
    $mail->Port = 587; // Common mail port
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    //$mail->SMTPDebug = 2; 
    $mail->Username = "serenitystyles.online@gmail.com"; // Sender's Email
    $mail->Password = "bisqzcfshomlyver"; // Sender's Email App Password
    $mail->SetFrom("serenitystyles.online@gmail.com"); // Sender's Email

    $mail->Subject = 'Appointment Cancellation Notice';
    $mail->Body = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            h2 {
                color: #4a4a4a;
                text-align: center;
            }
            .content {
                font-size: 16px;
                color: #555;
                line-height: 1.6;
                text-align: center;
            }
            .highlight {
                font-weight: bold;
                color: #c49a6c;
            }
            .footer {
                text-align: center;
                font-size: 14px;
                color: #888;
                margin-top: 20px;
                border-top: 1px solid #ddd;
                padding-top: 15px;
            }
        </style>
    </head>
    <body>
       <div class='container' style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9;'>
    <h2 style='color: #c49a6c;'>⚠️✨🌿 Serenity Styles - Appointment Cancellation 🌿✨⚠️</h2>
    <p class='content' style='font-size: 16px; color: #333;'>
        Dear Customer, <br><br>
        We regret to inform you that your appointment for <span class='highlight' style='color: #c49a6c; font-weight: bold;'>$service_name</span> 
        on <span class='highlight' style='color: #c49a6c; font-weight: bold;'>$date</span> at 
        <span class='highlight' style='color: #c49a6c; font-weight: bold;'>$time</span> has been <b>cancelled</b> due to the following reason: <br><br>
        <span class='highlight' style='color: red; font-weight: bold;'>$reason</span> <br><br>
        We sincerely apologize for any inconvenience this may cause. If you would like to reschedule  <a href='Visit:http://localhost/coffeeduplicate/userindex.php' style='color: #c49a6c;'>serenitystyles.com</a>
    </p>
    
    <div class='footer' style='margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 14px; color: #666;'>
        Thank you for understanding. We hope to serve you in the future! <br>
        <strong>Location:</strong> Hill top Street, Nilambur, Malappuram, Kerala, India<br>
        <strong>Phone:</strong> 8590918598<br>
        <strong>Website:</strong> <a href='http://localhost/coffeeduplicate/userindex.php' style='color: #c49a6c;'>Serenity Styles</a>
    </div>
</div>
    </body>
    </html>";

    $mail->AddAddress($to);  // To address of sending mail
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if (!$mail->Send()) {
        return $mail->ErrorInfo;
    } else {
        echo '<script>console.log("Cancellation mail sent")</script>';
        return "Cancellation mail sent successfully to: " . $to;
    }
}
?>
