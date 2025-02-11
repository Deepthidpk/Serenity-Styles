<?php
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    $name = trim($_POST['editname']);
    $phone = trim($_POST['editphone']);

    // Validate input
    if (empty($name) || empty($phone)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: profile.php");
        exit();
    }

    // Update the user's details in the database
    $stmt = $conn->prepare("UPDATE tbl_user SET name = ?, phone_no = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $name, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating profile.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to profile page
    header("Location: profile.php?user_id=$user_id");
    exit();

}
?>
