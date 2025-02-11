<?php
session_start(); // Start the session if not already started

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other desired page after logout
    header("Location: index.php");
    exit();
} 
