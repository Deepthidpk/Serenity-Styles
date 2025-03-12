<?php
include('connect.php');

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];
    $user_id = $_SESSION['user_id'];

    // Delete the product from the cart
    $sql = "UPDATE  tbl_cart SET status='Inactive' WHERE user_id = $user_id AND cart_id = $cart_id";

    if ($conn->query($sql)) {
        echo "<script>
              setTimeout(function() {
                  Swal.fire({
                      icon: 'success',
                      title: 'Removed from Cart!',
                      text: 'Product removed successfully!',
                      confirmButtonText: 'OK'
                  }).then(() => {
                      window.location.href = 'http://localhost/coffeeduplicate/cart.php';
                  });
              }, 500);
            </script>";
    } else {
        echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  text: 'Failed to remove product!',
                  confirmButtonText: 'OK'
              });
            </script>";
    }
}
?>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
