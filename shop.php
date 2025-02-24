<?php
include("connect.php");
$email=$_SESSION["email"];
$sql = "SELECT u.name FROM tbl_user AS u JOIN tbl_login AS l ON u.user_id = l.user_id WHERE l.email = '$email'";

$result=$conn->query($sql);
if ($result->num_rows > 0){
	$row=$result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title></title>
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
	<style>
		.product-entry a{
			max-width: 500px;
			max-height: 270px;
		}
		.product-entry a img{
			width: 100%;
			height: 100%;

		}
	</style>
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php">Beauty<small>Blend</small></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="userindex.php" class="nav-link">Home</a></li>
	          
	          

			  <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
			  
	          
	          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="shop.php" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Products</a>
              <div class="dropdown-menu" aria-labelledby="dropdown04">
              	<a class="dropdown-item" href="shop.php">Products</a>
                <a class="dropdown-item" href="product-single.php">Single Product</a>
                <a class="dropdown-item" href="cart.php">Cart</a>
                <a class="dropdown-item" href="checkout.php">Checkout</a>
              </div>
            </li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
			  <li class="nav-item"><a href="booknow.php" class="nav-link">Book Now</a></li>
	          <li class="nav-item cart"><a href="cart.php" class="nav-link"><span class="icon icon-shopping_cart"></span><span class="bag d-flex justify-content-center align-items-center"><small>1</small></span></a></li>
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
            	<h1 class="mb-3 mt-5 bread">Order Products</h1>
	            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Products</span></p>
            </div>

          </div>
        </div>
      </div>
    </section>


    <section class="ftco-menu mb-5 pb-5">
    	<div class="container">
    		<div class="row d-md-flex">
	    		<div class="col-lg-12 ftco-animate p-md-5">
		    		<div class="row">
		          <div class="col-md-12 nav-link-wrap mb-5">
		            <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
		            	<a class="nav-link active" id="v-pills-0-tab" data-toggle="pill" href="#v-pills-0" role="tab" aria-controls="v-pills-0" aria-selected="true">Facewash</a>

		              <a class="nav-link" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="false">Creams</a>

		              <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab" aria-controls="v-pills-2" aria-selected="false">Foundation</a>

		              <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab" aria-controls="v-pills-3" aria-selected="false">Shampoo</a>
		            </div>
		          </div>
		          <div class="col-md-12 d-flex align-items-center">
		            
		            <div class="tab-content ftco-animate" id="v-pills-tabContent">

		              <div class="tab-pane fade show active" id="v-pills-0" role="tabpanel" aria-labelledby="v-pills-0-tab">
		              	<div class="row">
						  <?php

// Fetch products from the database
$sql = "SELECT * FROM tbl_products WHERE category_id=1 AND status='available'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Escape values to prevent XSS
        $product_id = htmlspecialchars($row['product_id']);
        $product_name = htmlspecialchars($row['product_name']);
        $pro_description = htmlspecialchars($row['pro_description']);
        
        $price = htmlspecialchars($row['price']);
        $product_image = htmlspecialchars($row['product_image']); // Image path from DB

        // Check if image path is empty, use a default image
        $image_url = !empty($product_image) ? $product_image : 'images/default.jpg';

        // Generate the dynamic product card
        echo '
        <div class="col-md-4">
            <div class="product-entry">
                <a href="product-details.php?id=' . $product_id . '">
                    <img src="' . $image_url . '" class="img-fluid" alt="' . $product_name . '" style="width:100%; height:100%;">
                </a>
                <div class="text text-center pt-4">
                    <h3><a href="product-details.php?id=' . $product_id . '">' . $product_name . '</a></h3>
                    <p>' . $pro_description . '</p>
                    <p class="price"><span>Rs.' . number_format($price, 2) . '</span></p>
                  
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="' . $product_id . '">
                        
                        <button type="submit" class="btn btn-primary btn-outline-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>';
    }
} else {
    echo "<p class='text-center'>No products available.</p>";
}

?>

		              	</div>
		              </div>

		              <div class="tab-pane fade" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
		                <div class="row">
						<?php

// Fetch products from the database
$sql1 = "SELECT * FROM tbl_products WHERE category_id=2 AND status='available'";
$result = $conn->query($sql1);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Escape values to prevent XSS
        $product_id = htmlspecialchars($row['product_id']);
        $product_name = htmlspecialchars($row['product_name']);
        $pro_description = htmlspecialchars($row['pro_description']);
        
        $price = htmlspecialchars($row['price']);
        $product_image = htmlspecialchars($row['product_image']); // Image path from DB

        // Check if image path is empty, use a default image
        $image_url = !empty($product_image) ? $product_image : 'images/default.jpg';

        // Generate the dynamic product card
        echo '
        <div class="col-md-4">
            <div class="product-entry">
                <a href="product-details.php?id=' . $product_id . '">
                    <img src="' . $image_url . '" class="img-fluid" alt="' . $product_name . '" style="width:100%; height:100%;">
                </a>
                <div class="text text-center pt-4">
                    <h3><a href="product-details.php?id=' . $product_id . '">' . $product_name . '</a></h3>
                    <p>' . $pro_description . '</p>
                    <p class="price"><span>Rs.' . number_format($price, 2) . '</span></p>
                  
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="' . $product_id . '">
                        
                        <button type="submit" class="btn btn-primary btn-outline-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>';
    }
} else {
    echo "<p class='text-center'>No products available.</p>";
}

?>
		              	</div>
		              </div>
					  <div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
					  <div class="row">
		              <?php

// Fetch products from the database
$sql2 = "SELECT * FROM tbl_products WHERE category_id=3 AND status='available'";
$result = $conn->query($sql2);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Escape values to prevent XSS
        $product_id = htmlspecialchars($row['product_id']);
        $product_name = htmlspecialchars($row['product_name']);
        $pro_description = htmlspecialchars($row['pro_description']);
        
        $price = htmlspecialchars($row['price']);
        $product_image = htmlspecialchars($row['product_image']); // Image path from DB

        // Check if image path is empty, use a default image
        $image_url = !empty($product_image) ? $product_image : 'images/default.jpg';

        // Generate the dynamic product card
        echo '
        <div class="col-md-4">
            <div class="product-entry">
                <a href="product-details.php?id=' . $product_id . '">
                    <img src="' . $image_url . '" class="img-fluid" alt="' . $product_name . '" style="width:100%; height:100%;">
                </a>
                <div class="text text-center pt-4">
                    <h3><a href="product-details.php?id=' . $product_id . '">' . $product_name . '</a></h3>
                    <p>' . $pro_description . '</p>
                    <p class="price"><span>Rs.' . number_format($price, 2) . '</span></p>
                  
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="' . $product_id . '">
                        
                        <button type="submit" class="btn btn-primary btn-outline-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>';
    }
} else {
    echo "<p class='text-center'>No products available.</p>";
}

?>
		              	</div>
		              </div>

		              <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
		                <div class="row">
						<?php

// Fetch products from the database
$sql2 = "SELECT * FROM tbl_products WHERE category_id=4 AND status='available'";
$result = $conn->query($sql2);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Escape values to prevent XSS
        $product_id = htmlspecialchars($row['product_id']);
        $product_name = htmlspecialchars($row['product_name']);
        $pro_description = htmlspecialchars($row['pro_description']);
        
        $price = htmlspecialchars($row['price']);
        $product_image = htmlspecialchars($row['product_image']); // Image path from DB

        // Check if image path is empty, use a default image
        $image_url = !empty($product_image) ? $product_image : 'images/default.jpg';

        // Generate the dynamic product card
        echo '
        <div class="col-md-4">
            <div class="product-entry">
                <a href="product-details.php?id=' . $product_id . '">
                    <img src="' . $image_url . '" class="img-fluid" alt="' . $product_name . '" style="width:100%; height:100%;">
                </a>
                <div class="text text-center pt-4">
                    <h3><a href="product-details.php?id=' . $product_id . '">' . $product_name . '</a></h3>
                    <p>' . $pro_description . '</p>
                    <p class="price"><span>Rs.' . number_format($price, 2) . '</span></p>
                  
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="' . $product_id . '">
                        
                        <button type="submit" class="btn btn-primary btn-outline-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>';
    }
} else {
    echo "<p class='text-center'>No products available.</p>";
}

?>
		              		
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