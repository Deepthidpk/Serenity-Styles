<?php
require __DIR__ . "/vendor/autoload.php";
$client=new Google\Client;
$client->setClientId("91981920181-u001cgasvcrtcpblsfev8mhuccle262f.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/coffeeduplicate/userindex.php");
$client->addScope("email");

$client->addScope("profile");
$url=$client->createAuthUrl();


include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prevent SQL Injection (Use Prepared Statements)
  $stmt = $conn->prepare("SELECT * FROM tbl_login WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $demail = $row['email'];
    $dpassword = $row['password'];
    $status = $row['status'];

    // Check if user is active
    if ($status === 'Active') {
      if (password_verify($password, $dpassword)) {
        $_SESSION['email'] = $demail;
        if ($row['role'] == 'admin') {
            $_SESSION['username'] = "admin";
            header('Location: http://localhost/coffeeduplicate/admindashboard.php');
        } else if ($row['role'] == 'user') {
            $_SESSION['username'] = "user";
            $_SESSION['user_id'] = $row['user_id'];
            
            header('Location: userindex.php');
        } else {
            header('Location: login.php');
        }
        exit();
    } else {
        $error_message = "Invalid email or password!";
    }
} else {
  $error_message = "Your account is blocked. Please contact support.";
}
} else {
$error_message = "No active user found with this email!";
}

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>BeautyBlend - Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Use the same CSS files as your template -->
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

  <!-- Login Section -->
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 ftco-animate">
          <div class="heading-section text-center mb-5">
            <span class="subheading">Login Now</span>
          </div>

          <form action="" method="POST" class="appointment-form">
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="Your Email">
            </div>
            <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Your Password">
            </div>
            <div class="form-group d-flex justify-content-between">
              <div class="form-check">
                <!-- <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label> -->
              </div>
              <div>
                <a href="fpass.php" class="text-primary">Forgot Password?</a>
              </div>
            </div>
            <div class="form-group">
              <input type="submit" value="Login" class="btn btn-primary py-3 px-4 btn-block">
            </div>
            <div class="text-center mt-4">
              <p>Don't have an account? <a href="register.php" class="text-primary">Register here</a></p>
              <a href="<?= $url?>">Sign in with Google</a>
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

    <?php if (isset($_SESSION['error'])) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "<?php echo $_SESSION['error']; ?>"
        });
    <?php } ?>

    <?php if (isset($_SESSION['success'])) { ?>
        Swal.fire({
            icon: 'success',
            title: 'success...',
            text: "<?php echo $_SESSION['success']; ?>"
        });
    <?php } ?>
</script>
</body>
</html>