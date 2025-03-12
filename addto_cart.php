<?php
include('connect.php');
if(isset($_POST['product_id'])){
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the product already exists in the cart
    $sql = "SELECT * FROM tbl_cart WHERE user_id = $user_id AND product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Product exists in the cart, update the quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + 1;

        $sql = "UPDATE tbl_cart SET quantity = $new_quantity WHERE user_id = $user_id AND product_id = $product_id";
    } else {
        // Product does not exist in the cart, insert a new row
        $sql = "SELECT price FROM tbl_products WHERE product_id = $product_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        

        $sql = "INSERT INTO tbl_cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
    }

    if ($conn->query($sql)) {
        echo "<script>
              setTimeout(function() {
                  Swal.fire({
                      icon: 'success',
                      title: 'Added to Cart!',
                      text: 'Product added to cart successfully!',
                      confirmButtonText: 'OK'
                  }).then(() => {
                      window.location.href = 'http://localhost/coffeeduplicate/shop.php';
                  });
              }, 500);
            </script>";
    }
}
?>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
