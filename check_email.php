<?php
require("connect.php");

if(isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT * FROM tbl_login WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(false);
    } else {
        echo json_encode(true);
    }
} else {
    echo json_encode(false);
}

$conn->close();
?>