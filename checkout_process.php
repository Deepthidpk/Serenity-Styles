<?php
include('connect.php');
// Handle AJAX request for order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $address=$_POST['address'];
    $pincode = $_POST['zip'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $payment_id = $_POST['payment_id'];
    $cart_ids = json_decode( $_POST['cart_ids'],TRUE); // Convert to array


    // Insert user details into tbl_checkout
    $query1 = "INSERT INTO tbl_checkout (user_id,address, state, district, city, pincode, phone_no) 
               VALUES ('$user_id','$address', '$state', '$district', '$city', '$pincode', '$phone')";

    if ($conn->query($query1) === TRUE) {
        $checkout_id = $conn->insert_id; // Get last inserted ID
         foreach ($cart_ids as $cart_id): 
        $sql="INSERT INTO tbl_checkout_products(checkout_id,cart_id)VALUES($checkout_id,$cart_id)";
        $conn->query($sql);
        endforeach;

            

        
        // Insert payment details into tbl_payment
        $query2 = "INSERT INTO tbl_payment (user_id,checkout_id,success_key, amount) 
                   VALUES ('$user_id', '$checkout_id',  '$payment_id','$amount')";

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
