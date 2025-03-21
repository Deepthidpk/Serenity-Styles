<?php
include('connect.php');


// Set header for JSON response
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$appointment_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
    exit();
}

// Fetch the appointment details with service name
$query = "SELECT a.appointment_id, a.date, a.time, a.status, s.service_name 
          FROM tbl_appointment a
          JOIN tbl_services s ON a.service_id = s.service_id
          WHERE a.appointment_id = ? AND a.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $appointment_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();

if (!$appointment) {
    echo json_encode(['success' => false, 'message' => 'Appointment not found or does not belong to you']);
    exit();
}

// Check if the appointment date is in the future
$today = date('Y-m-d');
if (strtotime($appointment['date']) < strtotime($today)) {
    echo json_encode(['success' => false, 'message' => 'You cannot cancel past appointments']);
    exit();
}

// Check if the appointment is already cancelled
if ($appointment['status'] === 'Cancelled') {
    echo json_encode(['success' => false, 'message' => 'This appointment has already been cancelled']);
    exit();
}

// Update appointment status to "Cancelled"
$update_query = "UPDATE tbl_appointment SET status = 'Cancelled' WHERE appointment_id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("i", $appointment_id);

if ($update_stmt->execute()) {
    // Format the date and time for display
    $appointment['date'] = date('M d, Y', strtotime($appointment['date']));
    $appointment['time'] = date('h:i A', strtotime($appointment['time']));
    
    echo json_encode([
        'success' => true, 
        'message' => 'Appointment cancelled successfully',
        'appointment' => $appointment
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error cancelling appointment. Please try again']);
}

$update_stmt->close();
$stmt->close();
$conn->close();
?>