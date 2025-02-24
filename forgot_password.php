<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    include("reset_mail.php");
    // Connect to MySQL database
    require_once("connect.php");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username exists in the database
    $sql = "SELECT * FROM tbl_login WHERE email = '$email' AND status='Active'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
       
        $to = $user['email'];
     //Generate a unique reset password link
     $timestamp = time() + (2 * 3600); // Link expires in 24 hours
     
        //Generate a unique reset password link
        $resetLink = "http://localhost/coffeeduplicate/reset_password.php?email=" . urlencode($email) . "&timestamp=" . $timestamp;

        // Send email with the reset link
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";
        // Use PHP's mail() function or a library like PHPMailer to send the email
        smtp_mailer($to, $subject, $message);
       // echo "Password reset link has been sent to your email address.";
       $_SESSION['success'] = "Reset mail send to your mail successfully!";

       header('location:login.php');
    } else {
        $_SESSION['error'] = "username is invalid or blocked !";

        
        header('location:fpass.php');
    }

   
}
?>
<!-- HTML form for entering the username -->