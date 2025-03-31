<?php
include('connect.php');
// Handle AJAX request for order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $pincode = $_POST['zip'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $payment_id = $_POST['payment_id'];
    $products = json_decode($_POST['products'], TRUE); // Array with product_id and quantity

    // Insert user details into tbl_checkout
    $query1 = "INSERT INTO tbl_checkout (user_id, address, state, district, city, pincode, phone_no) 
               VALUES ('$user_id', '$address', '$state', '$district', '$city', '$pincode', '$phone')";

    if ($conn->query($query1) === TRUE) {
        $checkout_id = $conn->insert_id; // Get last inserted ID

        foreach ($products as $product) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];

            $sql = "INSERT INTO tbl_checkout_products (checkout_id, product_id, quantity) 
                    VALUES ($checkout_id, $product_id, $quantity)";
            if ($conn->query($sql)) {
                $sql2 = "UPDATE tbl_products SET quantity = quantity - $quantity WHERE product_id = $product_id";
                $conn->query($sql2);

            }

        }

        // Insert payment details into tbl_payment
        $query2 = "INSERT INTO tbl_payment (user_id, checkout_id, success_key, amount) 
                   VALUES ('$user_id', '$checkout_id', '$payment_id', '$amount')";

        if ($conn->query($query2) === TRUE) {
            echo "success";
        } else {
            echo "Payment Insert Error: " . $conn->error;
        }
    } else {
        echo "Checkout Insert Error: " . $conn->error;
    }

    $conn->close();
}
?>