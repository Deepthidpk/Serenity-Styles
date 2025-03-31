<?php
include('connect.php'); // Ensure this file connects to your database

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $time = $_POST['time'];

    if (!empty($date) && !empty($time)) {
        // Query to check if the selected date and time already exist in the database
        $query = "SELECT COUNT(*) AS count FROM tbl_appointment WHERE date = ? AND time = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $date, $time);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // If count is 0, the slot is available; otherwise, it's booked
        if ($row['count'] == 0) {
            echo json_encode(["available" => true]);
        } else {
            echo json_encode(["available" => false]);
        }
    } else {
        echo json_encode(["available" => false, "error" => "Invalid input"]);
    }
} else {
    echo json_encode(["available" => false, "error" => "Invalid request"]);
}
?>
