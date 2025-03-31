<?php
include("connect.php");

// Set header to return JSON
header('Content-Type: application/json');

// Validate and sanitize input
if(isset($_POST['date']) && !empty($_POST['date'])) {
    $date = $_POST['date'];
    
    // Get all booked times for the selected date
    $bookedTimes = array();
    $stmt = $conn->prepare("SELECT time FROM tbl_appointment WHERE date = ? AND status != 'Cancelled'");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row['time'];
    }
    
    $stmt->close();
    
    // Return JSON response with booked times
    echo json_encode(array("booked_times" => $bookedTimes));
} else {
    // Return error if date is not provided
    echo json_encode(array("error" => "Date parameter is required"));
}
?>