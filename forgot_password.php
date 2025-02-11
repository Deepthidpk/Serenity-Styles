

<?php
include 'connect.php';

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

?>