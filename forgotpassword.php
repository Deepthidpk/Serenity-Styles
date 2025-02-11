<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <form id="forgotPasswordForm" method="post">
            <h2>Reset Password</h2>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Send Reset Link</button>
            <div id="message"></div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="forgot_password.js"></script>
</body>
</html>

<!-- styles.css -->
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 350px;
}

.form-group {
    margin-bottom: 15px;
}

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#message {
    margin-top: 15px;
    text-align: center;
}

/* forgot_password.js */
$(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: 'forgot_password.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#message').html('<p style="color: green;">' + response.message + '</p>');
                    $('#email').val('');
                } else {
                    $('#message').html('<p style="color: red;">' + response.message + '</p>');
                }
            },
            error: function() {
                $('#message').html('<p style="color: red;">An unexpected error occurred.</p>');
            }
        });
    });
});

/* forgot_password.php */
<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email not found']);
        exit;
    }

    // Generate reset token
    $reset_token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store reset token
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $reset_token, $expiry, $email);
    
    if ($stmt->execute()) {
        // Send reset email (implementation depends on your email system)
        $reset_link = "https://yourwebsite.com/reset_password.php?token=" . $reset_token;
        
        // Email sending logic would go here
        // Use PHPMailer or similar library to send email

        echo json_encode([
            'status' => 'success', 
            'message' => 'Password reset link sent to your email'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to process request']);
    }

    $stmt->close();
    $conn->close();
}

/* db_connection.php */
<?php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

/* reset_password.php (snippet) */
<?php
require_once 'db_connection.php';

$token = $_GET['token'] ?? '';

// Verify token validity
$stmt = $conn->prepare("SELECT id, email, reset_token_expiry FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0 || strtotime($row['reset_token_expiry']) < time()) {
    // Handle invalid or expired token
}
?>