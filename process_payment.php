<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $payment_id = $_POST['payment_id'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $phone_no = $_POST['phone_no'];
    $status = 'Active';
    
    $conn->begin_transaction();
    
    try {
        // Insert into tbl_checkout
        $checkout_sql = "INSERT INTO tbl_checkout (user_id, address, state, district, city, pincode, phone_no, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $checkout_stmt = $conn->prepare($checkout_sql);
        $checkout_stmt->bind_param("isssssis", $user_id, $address, $state, $district, $city, $pincode, $phone_no, $status);
        $checkout_stmt->execute();
        $checkout_id = $conn->insert_id;
        
        // Insert into tbl_checkout_products
        $checkout_product_sql = "INSERT INTO tbl_checkout_products (product_id, checkout_id,quantity) VALUES (?, ?,?)";
        $checkout_product_stmt = $conn->prepare($checkout_product_sql);
        $checkout_product_stmt->bind_param("iii", $product_id, $checkout_id,$quantity);
        
        if ($checkout_product_stmt->execute()) {
            $sql2 = "UPDATE tbl_products SET quantity = quantity - $quantity WHERE product_id = $product_id";
            $conn->query($sql2);

        }
        // Insert into tbl_payment
        $payment_status = 'Success';
        $payment_date = date("Y-m-d H:i:s");
        $payment_sql = "INSERT INTO tbl_payment (user_id, checkout_id, amount, success_key, payment_date, status) VALUES (?, ?, ?, ?, ?, ?)";
        $payment_stmt = $conn->prepare($payment_sql);
        $payment_stmt->bind_param("iidsss", $user_id, $checkout_id, $total_price, $payment_id, $payment_date, $payment_status);
        $payment_stmt->execute();
        
        $conn->commit();
        echo "Order placed successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error processing order: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
