<?php

include 'connect.php'; // Ensure you have a database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone_no = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $payment_id = isset($_POST['payment_id']) ? trim($_POST['payment_id']) : '';
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : null;
    $date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
    $time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $status = 'Pending';
    

    

    $stmt = $conn->prepare("INSERT INTO tbl_appointment (user_id, service_id, name, phone_no, date, time, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssss", $user_id, $service_id, $name, $phone_no, $date, $time, $message, $status);

    if ($stmt->execute()) {
        $appointment_id = $conn->insert_id;
        $stmt->close();

        // Insert payment details
        $sts='Success';
        $stmt2 = $conn->prepare("INSERT INTO tbl_service_payment (appointment_id, token, user_id, amount, payment_date, status) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt2->bind_param("isids", $appointment_id, $payment_id, $user_id, $amount, $sts);
        
        if ($stmt2->execute()) {
            echo "success";
        } else {
            echo "error: " . $stmt2->error;
        }
        
        $stmt2->close();
    } else {
        echo "error: " . $stmt->error;
    }
    
    $conn->close();
} else {
    echo "error: Invalid request method";
}
?>
