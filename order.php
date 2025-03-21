<?php

require 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "Please log in to view your orders.";
    exit();
}

$user_id = $_SESSION["user_id"];

// Prepare the SQL query to fetch ordered items and their checkout IDs
$stmt = $conn->prepare("SELECT cp.checkout_id, p.product_id, p.product_name, p.price, p.product_image
    FROM tbl_products p
    INNER JOIN tbl_cart c ON p.product_id = c.product_id
    INNER JOIN tbl_checkout_products cp ON c.cart_id = cp.cart_id
    INNER JOIN tbl_payment py ON cp.checkout_id = py.checkout_id
    WHERE py.user_id = ?");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$ordered_items = [];
while ($row = $result->fetch_assoc()) {
    $ordered_items[] = $row;
}
$stmt->close();
$conn->close();

// Group ordered items by checkout_id
$grouped_items = [];
foreach ($ordered_items as $item) {
    $grouped_items[$item['checkout_id']][] = $item;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Orders</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="userindex.php">Beauty<small>Blend</small></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="userindex.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="shop.php" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="review_view.php" class="nav-link">Cart</a></li>
                    <li class="nav-item"><a href="cart.php" class="nav-link">Cart</a></li>
                    <li class="nav-item active"><a href="order.php" class="nav-link">Orders</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(images/coverpage1.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Your Orders</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="userindex.php">Home</a></span> <span>Orders</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mb-4">Your Orders</h2>
                    <?php if (!empty($grouped_items)): ?>
                        <?php foreach ($grouped_items as $checkout_id => $items): ?>
                            <div class="cart-list">
                                <h3 class="text-center">Checkout ID: <?= htmlspecialchars($checkout_id); ?></h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><img src="<?= htmlspecialchars($item['product_image']); ?>" width="100" alt="Product"></td>
                                                <td><?= htmlspecialchars($item['product_name']); ?></td>
                                                <td>Rs.<?= number_format($item['price'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <p class="text-center"><a href="payment_report.php?id=<?= $checkout_id; ?>" class="btn btn-primary py-3 px-4">Print</a></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
