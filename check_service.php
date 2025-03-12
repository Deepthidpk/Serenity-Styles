<?php
require("connect.php");

if(isset($_POST['service_name'])) {
    $service_name = $_POST['service_name'];
    $sql = "SELECT * FROM tbl_services WHERE status='active' AND service_name = '$service_name'";
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