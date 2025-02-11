<?php
include 'connect.php'; // Include your database connection

if (isset($_GET['id']) && isset($_GET['status'])) {
    $user_id = intval($_GET['id']);
    $status = ($_GET['status'] == 'Active') ? 'Active' : 'Inactive';

    $sql = "UPDATE tbl_login SET status = '$status' WHERE user_id = $user_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: viewuser.php"); // Redirect back to user list
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
