<?php
include("connect.php");

// Set header to return JSON
header('Content-Type: application/json');

// Validate and sanitize input
if(isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['time']) && !empty($_POST['time'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    // Check if the time slot is already booked
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tbl_appointment WHERE date = ? AND time = ? AND status != 'Cancelled'");
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $isAvailable = ($row['count'] == 0);
    
    $stmt->close();
    
    // Return availability status
    echo json_encode(array("available" => $isAvailable));
} else {
    // Return error if required parameters are not provided
    echo json_encode(array("error" => "Date and time parameters are required", "available" => false));
}
?>