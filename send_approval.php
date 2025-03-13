

   
<?php
include('smtp/PHPMailerAutoload.php');


function smtp_mailer($to,$time,$date,$service_name) //calling function for creating mail
{
    include('connect.php');
    
   
   
   
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
    $mail->Password = "bisqzcfshomlyver"; // Sender's Email App Password
    $mail->SetFrom("serenitystyles.online@gmail.com"); // Sender's Email

    $mail->Subject = 'Booking Confirmation';
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
            .button {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 20px;
                background-color: #c49a6c;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>✨🌿 Serenity Styles - Appointment Confirmation 🌿✨</h2>
            <p class='content'>
                Dear Customer, <br><br>
                Your appointment for <span class='highlight'>$service_name</span> on 
                <span class='highlight'>$appointment_date</span> at 
                <span class='highlight'>$appointment_time</span> has been successfully confirmed. <br><br>
                We look forward to pampering you at <strong>Serenity Styles</strong> and ensuring you have a relaxing and delightful experience. <br><br>
                If you have any questions or need to reschedule, please contact us at 
                <a href='mailto:serenitystyles.online@gmail.com' style='color: #c49a6c;'>serenitystyles.online@gmail.com</a>.
            </p>
            
            <div class='footer'>
                Thank you for choosing Serenity Styles! <br>
                <strong>Location:</strong> Hill top Street ,Nilambur,Malappuram,Kerala,India<br>
                <strong>Phone:</strong> 8590918598<br>
                <strong>Website:</strong> <a href='http://localhost/coffeeduplicate/index.php' style='color: #c49a6c;'>Serenity Styles</a>
            </div>
        </div>
    </body>
    </html>";


    $mail->AddAddress($to);  //to address of sending mail
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if (!$mail->Send()) {   //
        return $mail->ErrorInfo;
    } else {
        echo '<script>console.log("sended")</script>';
        return "Confirmation sended  Successfully !:"  . $to;
        
    }
}
// $conn->close();
// session_abort();
?>