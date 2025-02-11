<?php
include 'connect.php';


$id = $_GET['id'];
$sql="UPDATE `tbl_products` SET `status`='unavailable' WHERE `product_id`=$id";


if (mysqli_query($conn, $sql)) {
    echo "Record deleted successfully";
    header("Location: viewproducts.php");
    exit;
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}


mysqli_close($conn);
?>
