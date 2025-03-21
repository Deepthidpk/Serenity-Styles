<?php
include 'connect.php'; // Include your database connection

if (isset($_POST['selected_date'])) {
    $selected_date = $_POST['selected_date'];
    
    // Fetch booked time slots for the selected date
    $query = "SELECT time FROM tbl_appointment WHERE date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $booked_slots = [];
    while ($row = $result->fetch_assoc()) {
        $booked_slots[] = $row['time'];
    }

    echo json_encode($booked_slots);
}
?>
