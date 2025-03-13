<?php

require 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "Please log in to view your cart.";
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch cart items from the database
$stmt = $conn->prepare("
    SELECT c.cart_id, p.product_name, p.price, c.quantity, p.quantity AS qty,p.product_image, (p.price * c.quantity) AS total_price 
    FROM tbl_cart c
    JOIN tbl_products p ON c.product_id = p.product_id
    WHERE c.status='Active' AND c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate subtotal
$subtotal = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $subtotal += $row['total_price'];
    $cart_items[] = $row;
}

$stmt->close();
$conn->close();

$delivery_fee = 0.00;
$total = $subtotal + $delivery_fee;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
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
	          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
	          <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="shop.php" id="dropdown04" data-toggle="dropdown">Products</a>
              <div class="dropdown-menu">
              	<a class="dropdown-item" href="shop.php">Products</a>
                <a class="dropdown-item" href="cart.php">Cart</a>
                <a class="dropdown-item" href="checkout.php">Checkout</a>
              </div>
            </li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
            <li class="nav-item"><a href="booknow.php" class="nav-link">Book Now</a></li>
            <li class="nav-item"><a href="review.php" class="nav-link">Reviews</a></li>
			  
	          <li class="nav-item cart"><a href="cart.php" class="nav-link"><span class="icon icon-shopping_cart"></span><span class="bag d-flex justify-content-center align-items-center"><small><?= count($cart_items) ?></small></span></a></li>
	        </ul>
	      </div>
		  </div>
	  </nav>
    <!-- END nav -->

    <section class="home-slider owl-carousel">
      <div class="slider-item" style="background-image: url(images/coverpage1.jpg);" data-stellar-background-ratio="0.5">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center">
            <div class="col-md-7 col-sm-12 text-center ftco-animate">
            	<h1 class="mb-3 mt-5 bread">Cart</h1>
	            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Cart</span></p>
            </div>
          </div>
        </div>
      </div>
    </section>

		<section class="ftco-section ftco-cart">
			<div class="container">
				<div class="row">
    			<div class="col-md-12 ftco-animate">
    				<div class="cart-list">
	    				<table class="table">
						    <thead class="thead-primary">
						      <tr class="text-center">
						        <th>&nbsp;</th>
						        <th>&nbsp;</th>
						        <th>Product</th>
						        <th>Price</th>
						        <th>Quantity</th>
						        <th>Total</th>
						      </tr>
						    </thead>
						    <tbody>
                <?php if (count($cart_items) > 0): ?>
    <?php foreach ($cart_items as $item): ?>
        <?php
           
            $available_stock = $item['qty']; // Available stock from database
            
        ?>

        <tr class="text-center">
            <td class="product-remove">
                <form method="POST" action="remove_cart.php">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                    <button type="submit" style="border: none; background: none;">
                        <span class="icon-close"></span>
                    </button>
                </form>
            </td>

            <td class="image-prod">
                <div class="img" style="background-image:url(<?= htmlspecialchars($item['product_image']); ?>);"></div>
            </td>

            <td class="product-name">
                <h3><?= htmlspecialchars($item['product_name']); ?></h3>
            </td>

            <td class="price">Rs.<?= number_format($item['price'], 2); ?></td>

            <td class="quantity">
                <form method="POST" action="update_cart.php" style="display: flex; align-items: center;">
                    <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">

                    <!-- Decrease Button -->
                    <button type="submit" name="action" value="decrease" style="border: none; background: none; font-size: 18px;">➖</button>

                    <input type="text" name="quantity" class="quantity form-control input-number" 
                        value="<?= $item['quantity']; ?>" min="1" max="<?= $available_stock; ?>" readonly 
                        style="width: 40px; text-align: center; border: none; background: transparent;">

                    <!-- Increase Button: Disabled if quantity >= available stock -->
                    <button type="submit" name="action" value="increase" 
                        style="border: none; background: none; font-size: 18px;" 
                        <?= ($item['quantity'] >= $available_stock) ? 'disabled' : ''; ?>>➕</button>
                </form>
            </td>

            <td class="total">Rs.<?= number_format($item['total_price'], 2); ?></td>
        </tr>
    <?php endforeach; ?>




						    <?php else: ?>
						      <tr><td colspan="6" class="text-center">Your cart is empty.</td></tr>
						    <?php endif; ?>
						    </tbody>
						  </table>
					  </div>
    			</div>
    		</div>
    		<div class="row justify-content-end">
    			<div class="col col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
    				<div class="cart-total mb-3">
    					<h3>Cart Totals</h3>
    					<p class="d-flex"><span>Subtotal</span><span>Rs.<?= number_format($subtotal, 2); ?></span></p>
    					<p class="d-flex"><span>Delivery</span><span>Rs.<?= number_format($delivery_fee, 2); ?></span></p>
    					<hr>
    					<p class="d-flex total-price"><span>Total</span><span>Rs.<?= number_format($total, 2); ?></span></p>
    				</div>
    				<p class="text-center"><a href="checkout.php" class="btn btn-primary py-3 px-4">Proceed to Checkout</a></p>
    			</div>
    		</div>
			</div>
		</section>
		<footer class="ftco-footer ftco-section img">
    	<div class="overlay"></div>
      <div class="container">
        <div class="row mb-5">
          <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">About Us</h2>
              <p>We offers a relaxing atmosphere with professional services, including haircuts, facials, and manicures. The salon is known for its friendly staff, quality treatments, and attention to detail.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
         
          <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
             <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Services</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Haircut</a></li>
                <li><a href="#" class="py-2 d-block">Facial</a></li>
                <li><a href="#" class="py-2 d-block">Manicure</a></li>
                <li><a href="#" class="py-2 d-block">Makeup</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Have a Questions?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text"> Hill top Street<br>Nilambur,<br>Malappuram,<br>Kerala,<br>India</span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">8590918598</span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

           
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

    
  </body>
</html>
