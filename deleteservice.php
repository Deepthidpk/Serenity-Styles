<?php
include 'connect.php';


$id = $_GET['id'];
$sql="UPDATE `tbl_services` SET `status`='Inactive' WHERE `service_id`=$id";


if (mysqli_query($conn, $sql)) {
    echo "Record deleted successfully";
    header("Location: viewservices.php");
    exit;
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}


mysqli_close($conn);
?>
