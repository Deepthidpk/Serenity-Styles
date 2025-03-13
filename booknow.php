<?php

include("connect.php");

if (!empty($_SESSION["email"])) {
    $email = $_SESSION["email"];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT u.name FROM tbl_user AS u JOIN tbl_login AS l ON u.user_id = l.user_id WHERE l.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }

    $stmt->close();
}

// Sanitize GET parameters
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : null;
    $date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : null; 
   $name=isset($_POST['full_name']) ? trim($_POST['full_name']) : null;
   $phone_no=isset($_POST['phone']) ? intval($_POST['phone']) : null;
    $time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : null;
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : null;
    $status = 'Pending';

    // Ensure required fields are provided
    // if (!$user_id || !$service_id || !$date || !$time) {
    //   echo "hii";
    //     echo "<script>
    //             Swal.fire('Error!', 'All fields are required!', 'error');
    //           </script>";
    //     exit();
    // }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO tbl_appointment (user_id, service_id, name,phone_no,date, time, message, status) VALUES (?, ?, ?, ?, ?, ?,?,?)");
    $stmt->bind_param("iissssss", $user_id, $service_id, $name,$phone_no,$date, $time, $message, $status);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Appointment Booked!',
                    text: 'Your appointment has been successfully submitted.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'booknow.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire('Error!', 'Failed to book appointment. Please try again.', 'error');
              </script>";
    }

    $stmt->close();
    $conn->close();
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
  </head>
  <body>
  	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="userindex.php">Beauty<small>Blend</small></a>
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
			  <li class="nav-item active"><a href="booknow.php" class="nav-link">Book Now</a></li>
        <li class="nav-item"><a href="review.php" class="nav-link">Reviews</a></li>

			  <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'user') { ?>

<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false">
    <img src="images/profile2.jpg" alt="Profile" id="profile-icon" class="rounded-circle"
      style="width: 30px; height: 30px;">
  </a>
  <?php
  $user_id = $_SESSION['user_id'];
  ?>
  <div class="dropdown-menu" aria-labelledby="profileDropdown">
    <a class="dropdown-item"
      href="profile.php?user_id=<?php echo $user_id; ?>"><?php echo $row['name']; ?></a>
    <a class="dropdown-item" href="profile.php?user_id=<?php echo $user_id; ?>">Profile</a>

    <a class="dropdown-item" href="viewappointments.html?user_id=<?php echo $user_id; ?>">View
      Appointments</a>
    <a class="dropdown-item" href="vieworders.html?user_id=<?php echo $user_id; ?>">View
      Orders</a>
    <a class="dropdown-item" href="logout.php?user_id=<?php echo $user_id; ?>">Log Out</a>
  </div>

</li>
<?php }

?>
<?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'user') { ?>

  <li class="nav-item cart"><a href="cart.php" class="nav-link"><span
        class="icon icon-shopping_cart"></span><span
        class="bag d-flex justify-content-center align-items-center"><small>1</small></span></a>
  </li>
<?php }

?>	        </ul>
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
            	<h1 class="mb-3 mt-5 bread">BOOK APPOINTMENT</h1>
	            <p class="breadcrumbs"><span class="mr-2"><a href="userindex.php">Home</a></span> <span>Book Now</span></p>
            </div>

          </div>
        </div>
      </div>
    </section>

	<section class="ftco-intro">
    	<div class="container-wrap">
    		<div class="wrap d-md-flex align-items-xl-end">
	    		<div class="info">
	    			<div class="row no-gutters">
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-phone"></span></div>
	    					<div class="text">
	    						<h3>8590918598</h3>
	    						<p>"Experience luxury and transformation at our beauty salon, where expert care unveils your radiant glow."</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-my_location"></span></div>
	    					<div class="text">
	    						<h3>Hill top Street</h3>
	    						<p>	Nilambur,<br>Malappuram,<br>Kerala,<br>India</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-clock-o"></span></div>
	    					<div class="text">
	    						<h3>Open Monday-Saturday</h3>
	    						<p>8:00am - 6:00pm</p>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="book p-4">
	    			<h3>Book an Appointment</h3>
            
	    			<form action="#" method="POST"class="appointment-form">
            <input type="hidden" name="service_id" value="<?php echo $service_id;?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id;?>"> 
    <div class="d-md-flex">
        <div class="form-group">
            <input type="text" name="full_name" class="form-control" placeholder="Full Name">
        </div>
    </div>
    <div class="d-md-flex">
        <div class="form-group">
            <div class="input-wrap">
                <!-- <div class="icon"><span class="ion-md-calendar"></span></div> -->
                <input type="date" name="appointment_date" class="form-control" placeholder="Date">
            </div>
        </div>
        <div class="form-group ml-md-4">
            <div class="input-wrap">
                <div class="icon"><span class="ion-ios-clock"></span></div>
                <input type="text" name="appointment_time" class="form-control appointment_time" placeholder="Time">
            </div>
        </div>
        <div class="form-group ml-md-4">
            <input type="text" name="phone" class="form-control" placeholder="Phone">
        </div>
    </div>
    <div class="d-md-flex">
        <div class="form-group">
            <textarea name="message" cols="30" rows="2" class="form-control" placeholder="Message"></textarea>
        </div>
        <div class="form-group ml-md-4">
            <input type="submit" value="Appointment" class="btn btn-white py-3 px-4">
        </div>
    </div>
</form>

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
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+2 392 3929 210</span></a></li>
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
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
  <!-- jQuery & Validation Plugin -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>



<script>
$(document).ready(function () {
    // Custom validation methods
    jQuery.validator.addMethod('lettersonly', function (value, element) {
        return /^[^-\s][a-zA-Z_\s-]+$/.test(value);
    }, "Please use letters only.");

    jQuery.validator.addMethod('indianPhone', function (value, element) {
        return /^[6-9]\d{9}$/.test(value);
    }, "Please enter a valid Indian phone number starting with 6, 7, 8, or 9.");

    jQuery.validator.addMethod('validDate', function (value, element) {
        let selectedDate = new Date(value);
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time to midnight for comparison
        return selectedDate >= today;
    }, "Please select a present or future date.");

    jQuery.validator.addMethod('validTime', function (value, element) {
        let selectedTime = value.split(':');
        let hours = parseInt(selectedTime[0], 10);
        let minutes = parseInt(selectedTime[1], 10);

        return (hours >= 4 && hours < 22) || (hours === 22 && minutes === 0);
    }, "Please select a time between 4 AM and 10 PM.");

    $(".appointment-form").validate({
        rules: {
            full_name: {
                lettersonly: true,
                required: true,
                minlength: 3
            },
            appointment_date: {
                required: true,
                date: true,
                validDate: true
            },
            appointment_time: {
                required: true,
                validTime: true
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
                indianPhone: true
            },
            message: {
                minlength: 5
            }
        },
        messages: {
            full_name: {
                required: "Please enter your full name",
                lettersonly: "Name must be in alphabets only",
                minlength: "Name should be at least 3 characters long"
            },
            appointment_date: {
                required: "Please select a date",
                validDate: "Date must be today or in the future"
            },
            appointment_time: {
                required: "Please select a time",
                validTime: "Time should be between 4 AM and 10 PM"
            },
            phone: {
                required: "Phone number is required",
                digits: "Please enter only numbers",
                minlength: "Invalid phone number",
                maxlength: "Invalid phone number",
                indianPhone: "Please enter a valid phone number starting with 6, 7, 8, or 9."
            },
            message: {
                minlength: "Message should be at least 5 characters long"
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            error.insertAfter(element);
        },
        // submitHandler: function (form) {
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Appointment Booked!',
        //         text: 'Your appointment has been successfully submitted.',
        //         confirmButtonText: 'OK'
        //     }).then(() => {
        //         window.location.href = "booknow.php";
        //     });

        //     return false; // Prevent actual form submission
        // }
    });

    // Prevent selecting past dates in the input field
    let today = new Date().toISOString().split("T")[0];
    $("#appointment_date").attr("min", today);
});
</script>

  </body>
</html>

   