<!DOCTYPE html>
<html lang="en">
<head>
  <title>BeautyBlend - Password Reset</title>
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
  <?php
  include "connect.php";
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
      $newPassword = $_POST['new_password'];
      $confirmPassword = $_POST['confirm_password'];

      if ($newPassword === $confirmPassword) {
          $email = $_POST["email"];
          if(isset($email)){
              $sql = "UPDATE tbl_login SET password = '$newPassword' WHERE email = '$email'";
              $result = $conn->query($sql);

              if ($result) {
                  echo "<script>Swal.fire('Success!', 'Password updated successfully! You can now login with your new password.', 'success');</script>";
                  header("Refresh:5; url=login.php");
              } else {
                  echo "<script>Swal.fire('Error!', 'Passwords do not match!', 'error');</script>";
              }
          }
      }
  }
  ?>

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

  <!-- Password Reset Section -->
  <section class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 ftco-animate">
          <div class="heading-section text-center mb-5">
            <span class="subheading">Reset Password</span>
          </div>
          <?php
         
         if (isset($_GET['email']) && isset($_GET['timestamp'])) {
             $email = $_GET['email'];
             $timestamp = $_GET['timestamp'];

         
             if (time() < $timestamp) {
         ?>
                 <form id="resetform" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="appointment-form">
                     <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" hidden>
                     <div class="form-group">
                         <label for="new_password">New Password</label>
                         <input type="password" id="new_password" name="new_password" class="form-control">
                     </div>
                     <div class="form-group">
                         <label for="confirm_password">Confirm Password</label>
                         <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                     </div>
                     <div class="form-group">
                         <input type="submit" name="reset_password" value="Reset Password" class="btn btn-primary py-3 px-4 btn-block">
                     </div>
                 </form>
         <?php
             } else {
                 // Expired link message
                 echo "<script>
                         Swal.fire('Error!', 'The reset link has expired. Please request a new one.', 'error');
                       </script>";
                 // Ensure no output is sent before header()
                 ob_start();
                 header("Refresh:5; url=login.php");
                 ob_end_flush();
                 exit();
             }
         }
         ?>
         
          
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

  <!-- jQuery Validation -->
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
  <script>
    $(document).ready(function () {
        jQuery.validator.addMethod('all', function(value, element) {
            return /^[^-\s][a-zA-Z0-9_!@#$%^&*(),.?":{}|<>-]+$/.test(value);
        });
        jQuery.validator.addMethod('strongPassword', function (value, element) {
    return this.optional(element) || 
           /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/.test(value);
}, "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.");

        $('#resetform').validate({
            rules: {
                new_password: {
                    required: true,
                    minlength: 8,
                    strongPassword: true,
                    all: true 
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password",
                }
            },
            messages: {
                new_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 8 characters long",
                    strongPassword: "Password must contain uppercase, lowercase, digit, and special character.",

                    all: "Spaces are not allowed in the password"
                },
                confirm_password: {
                    required: "Please re-enter the password",
                    equalTo: "Password and Confirm Password Field do not match"
                }
            }
        });
    });
  </script>
</body>
</html>