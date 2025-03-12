<?php
include 'connect.php'; // Include your database connection

if (isset($_GET['id']) && isset($_GET['status'])) {
    $appointment_id = intval($_GET['id']);
    $status = ($_GET['status'] == 'Approved') ? 'Approved' : 'Cancelled';

    $sql = "UPDATE tbl_appointment SET status = '$status' WHERE appointment_id = $appointment_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_appointment.php"); // Redirect back to user list
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>