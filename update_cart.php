<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'], $_POST['user_id'], $_POST['action'])) {
    $cart_id = intval($_POST['cart_id']);
    $user_id = intval($_POST['user_id']);
    $action = $_POST['action'];

    // Get current quantity
    $sql = "SELECT quantity FROM tbl_cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_quantity = $row['quantity'];
    $stmt->close();

    if ($action == 'increase') {
        $new_quantity = $current_quantity + 1;
    } elseif ($action == 'decrease') {
        $new_quantity = max(1, $current_quantity - 1); // Ensure quantity doesn't go below 1
    }

    // Update the quantity
    $sql = "UPDATE tbl_cart SET quantity = ? WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);

    if ($stmt->execute()) {
        header('Location:cart.php');
    }
    

    $stmt->close();
}
?>


