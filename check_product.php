<?php
require("connect.php");

if(isset($_POST['product_name'])) {
    $product_name = $_POST['product_name'];
    $sql = "SELECT * FROM tbl_products WHERE status='available' AND product_name = '$product_name'";
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