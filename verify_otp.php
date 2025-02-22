<?php 
require 'connect.php';
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $otp = $_POST["otp"];
    
    $stmt = $conn->prepare("SELECT otp, created_at FROM tbl_verification WHERE email=? AND otp=?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_otp, $created_at);
        $stmt->fetch();
        $stmt->close();
        
        if (!empty($created_at)) {
            $otp_time = strtotime($created_at);
            $current_time = time();
            $time_difference = $current_time - $otp_time;
            
            if ($time_difference <= 600) {
                $update_stmt = $conn->prepare("UPDATE tbl_verification SET status='verified' WHERE email=?");
                $update_stmt->bind_param("s", $email);
                $update_stmt->execute();
                $update_stmt->close();
                $update_stmt=$conn->prepare("UPDATE tbl_login SET status='Active' WHERE email=?");
                $update_stmt->bind_param("s", $email);
                $update_stmt->execute();
                $update_stmt->close();
                
                $success_message = "Email verification successful!";
            } else {
                $error_message = "OTP Expired! Please request a new OTP.";
            }
        } else {
            $error_message = "Invalid verification attempt.";
        }
    } else {
        $error_message = "Invalid OTP! Please check and try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>BeautyBlend - Email Verification</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSS Files -->
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

    <!-- OTP Verification Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 ftco-animate">
                    <div class="heading-section text-center mb-5">
                        <span class="subheading">Verify Your Email</span>
                        
                    </div>
                    <form action="" method="POST" class="appointment-form">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Verify OTP" class="btn btn-primary py-3 px-4 btn-block">
                        </div>
                        <div class="text-center mt-4">
                            <p>Didn't receive OTP? <a href="resend_otp.php" class="text-primary">Resend OTP</a></p>
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
        <?php if (isset($success_message)) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?php echo $success_message; ?>",
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'http://localhost/coffeeduplicate/login.php';
            });
        <?php } ?>

        <?php if (isset($error_message)) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "<?php echo $error_message; ?>",
                confirmButtonText: 'OK'
            });
        <?php } ?>
    </script>
</body>
</html>