<!-- <?php
include("connect.php");

// Fetch user details
if(isset($_GET["user_id"])){
$user_id = $_GET["user_id"];
$_SESSION["user_id"] = $user_id;
}
else{
    echo "user_id not fetched";
}

$query = "SELECT 
    u.user_id, u.name, u.phone_no, 
    l.email, l.role, l.status 
FROM tbl_user u
JOIN tbl_login l ON u.user_id = l.user_id
WHERE u.user_id = ?;
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BeautyBlend - Profile</title>
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
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">Beauty<small>Blend</small></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="userindex.php" class="nav-link">Home</a></li>
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
                
                <?php if(isset($_SESSION['username'])){?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="images/profile2.jpg" alt="Profile" id="profile-icon" class="rounded-circle" style="width: 30px; height: 30px;">
        </a>
        <?php
		$user_id=$_SESSION['user_id'];
		?>
<div class="dropdown-menu" aria-labelledby="profileDropdown">
    <a class="dropdown-item" href="profile.php?user_id=<?php echo $user_id; ?>">Profile</a>
   
    <a class="dropdown-item" href="viewappointments.html?user_id=<?php echo $user_id; ?>">View Appointments</a>
    <a class="dropdown-item" href="vieworders.html?user_id=<?php echo $user_id; ?>">View Orders</a>
    <a class="dropdown-item" href="logout.php?user_id=<?php echo $user_id; ?>">Log Out</a>
</div>

    </li> <?php } ?>



                <li class="nav-item cart">
                    <a href="cart.php" class="nav-link">
                        <span class="icon icon-shopping_cart"></span>
                        <span class="bag d-flex justify-content-center align-items-center">
                            <small>1</small>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(images/coverpage1.jpg);" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">My Profile</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Profile</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Section -->
    <section class="ftco-section">
    <form action="editprofile.php" id="profile-form"method="POST">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 ftco-animate">
                <div class="billing-form ftco-bg-dark p-3 p-md-5">
                    <h3 class="mb-4 billing-heading">Profile Details</h3>
                    <div class="row align-items-end">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="editname" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="editphone" class="form-control" value="<?php echo htmlspecialchars($user['phone_no'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary py-3 px-4">Save Changes</button>
                            <a href="change-password.php" class="btn btn-secondary py-3 px-4">Change Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

               
    </section>

    <!-- Footer -->
    <!-- <?php include 'footer.php'; ?> -->

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $_SESSION['success']; ?>',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php elseif (isset($_SESSION['error'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $_SESSION['error']; ?>',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <script>
    $(document).ready(function () {
    // Custom validation methods
    jQuery.validator.addMethod('lettersonly', function (value, element) {
        return /^[A-Za-z\s-]+$/.test(value);  // Ensures only letters, spaces, and hyphens
    }, "Please use letters only.");

    jQuery.validator.addMethod('indianPhone', function (value, element) {
        return /^[6-9]\d{9}$/.test(value); // Ensures number starts with 6-9 and has exactly 10 digits
    }, "Please enter a valid Indian phone number.");

    // Apply validation only if the form exists
    if ($("#profile-form").length) {
        $('#profile-form').validate({
            rules: {
                editname: {
                    required: true,
                    lettersonly: true,
                    minlength: 3
                },
                editphone: {
                    required: true,
                    indianPhone: true
                }
            },
            messages: {
                editname: {
                    required: "Please enter your full name",
                    minlength: "Name must have at least 3 letters",
                    lettersonly: "Name must contain only alphabets"
                },
                editphone: {
                    required: "Phone number is required",
                    indianPhone: "Please enter a valid 10-digit phone number starting with 6, 7, 8, or 9."
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element); // Places error messages after the input field
            }
        });
    }
});
</script>
  
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    
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
    <script src="js/main.js"></script>
   
</body>
</html>