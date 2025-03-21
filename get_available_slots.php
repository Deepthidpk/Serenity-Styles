<?php
include("connect.php");

// Get the date from POST request
$date = isset($_POST['date']) ? trim($_POST['date']) : '';

// Initialize response arrays
$response = array(
    'available' => array(),
    'booked' => array()
);

if (!empty($date)) {
    // Define business hours (8 AM to 6 PM)
    $start_hour = 8;
    $end_hour = 18;
    $interval = 60; // 60 minutes per appointment
    
    // Generate all possible time slots
    $all_slots = array();
    for ($hour = $start_hour; $hour < $end_hour; $hour++) {
        $time = sprintf("%02d:00", $hour);
        $all_slots[] = $time;
    }
    
    // Get booked slots from database
    $booked_slots = array();
    $stmt = $conn->prepare("SELECT time FROM tbl_appointment WHERE date = ? AND status != 'Cancelled'");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $booked_slots[] = $row['time'];
    }
    
    $stmt->close();
    
    // Filter available slots
    $available_slots = array_diff($all_slots, $booked_slots);
    
    // Update response
    $response['available'] = array_values($available_slots); // Reset array keys
    $response['booked'] = $booked_slots;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>