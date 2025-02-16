<!DOCTYPE html>
<html lang="en">

<head>
  <title>BeautyBlend - Forgot Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- CSS files -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
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
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.html">Beauty<small>Blend</small></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
        aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->

  <!-- Forgot Password Section -->
  <section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 ftco-animate">
        <div class="heading-section text-center mb-5">
          <span class="subheading">Forgot Password</span>
        </div>
        <div class="text-center mb-4">
          <p>Enter your email address and we'll send you instructions to reset your password.</p>
        </div>
        <form action="forgot_password.php" method="POST" class="appointment-form">
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
          </div>
          <div class="form-group">
            <input type="submit" value="Reset Password" class="btn btn-primary py-3 px-4 btn-block">
          </div>
          <div class="text-center mt-4">
            <p>Remember your password? <a href="login.php" class="text-primary">Login here</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

  <!-- JavaScript files -->
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
  <script src="js/main.js"></script>

  <!-- SweetAlert2 Script -->
  <script>
    <?php if (isset($error_message)) { ?>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "<?php echo $error_message; ?>"
      });
    <?php } ?>
  </script>
</body>
</html>