<?php
include 'connect.php'; 



// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Get form data
//     $name = $_POST['name'];
//     $phone_no =$_POST['phone_no'];
//     $email = $_POST['email'];
//     $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

//     $conpass = $_POST['conpass'];
     
    
   
//         $sql="INSERT INTO tbl_user(name,phone_no)VALUES('$name','$phone_no')";
//         $conn->query($sql);
//         $user_id=$conn->insert_id;
//         $query="INSERT INTO tbl_login(user_id,email,password,role)VALUES('$user_id','$email','$pass','user')";
//         $conn->query($query);
       
//         if ($conn) {
//             require "send_otp.php";
//             smtp_mailer($email);
        
//             // SweetAlert script
//             echo "<script>
//                     setTimeout(function() {
//                         Swal.fire({
//                             icon: 'success',
//                             title: 'OTP Sent!',
//                             text: 'Check your email for the OTP.',
//                             confirmButtonText: 'OK'
//                         }).then(() => {
//                             window.location.href = 'http://localhost/coffeeduplicate/verify_otp.php';
//                         });
//                     }, 500);
//                   </script>";
//         }
        
        

//     }
        
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $name = $_POST['name'];
  $phone_no = $_POST['phone_no'];
  $email = $_POST['email'];
  $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // 🔒 Password hashed here
  $conpass = $_POST['conpass'];
  
  $sql = "INSERT INTO tbl_user(name, phone_no) VALUES('$name', '$phone_no')";
  $conn->query($sql);
  $user_id = $conn->insert_id;

  $query = "INSERT INTO tbl_login(user_id, email, password, role) VALUES('$user_id', '$email', '$pass', 'user')";
  $conn->query($query);

  if ($conn) {
      require "send_otp.php";
      smtp_mailer($email);
      
      echo "<script>
              setTimeout(function() {
                  Swal.fire({
                      icon: 'success',
                      title: 'OTP Sent!',
                      text: 'Check your email for the OTP.',
                      confirmButtonText: 'OK'
                  }).then(() => {
                      window.location.href = 'http://localhost/coffeeduplicate/verify_otp.php';
                  });
              }, 500);
            </script>";
  }
}

   

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>BeautyBlend - Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    

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
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.php">Beauty<small>Blend</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto">
           
          </ul>
        </div>
      </div>
    </nav>
    <!-- END nav -->

    <!-- Registration Section -->
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 ftco-animate">
            <div class="heading-section text-center mb-5">
              <span class="subheading">Register Now</span>
              
            </div>
            <form action="#" method="POST"id="myform"class="appointment-form">
              <div class="form-group">
                <input type="text" name="name"class="form-control" placeholder="Your Full Name">
              </div>
              <div class="form-group">
                <input type="email" name="email"class="form-control" placeholder="Your Email">
              </div>
              <div class="form-group">
                <input type="tel" name="phone_no"class="form-control" placeholder="Your Phone Number">
              </div>
              
              <div class="form-group">
                <input type="password" id="pass"name="pass"class="form-control" placeholder="Choose Password">
              </div>
              <div class="form-group">
                <input type="password" id="conpass"name="conpass"class="form-control" placeholder="Confirm Password">
              </div>
              <div class="form-group">
                <input type="submit" value="Register" class="btn btn-primary py-3 px-4 btn-block">
              </div>
              <div class="text-center mt-4">
                <p>Already have an account? <a href="login.php" class="text-primary">Login here</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
    // Custom validation methods
    jQuery.validator.addMethod('lettersonly', function (value, element) {
        return /^[^-\s][a-zA-Z_\s-]+$/.test(value);
    }, "Please use letters only.");

    jQuery.validator.addMethod('customEmail', function (value, element) {
        return /^[^0-9][a-zA-Z0-9._%+-]+@(gmail|yahoo|mca.ajce)(\.com|\.in)$/i.test(value);
    }, "Please enter a valid email address with Gmail, Yahoo, or mca.ajce.in domain, and the first character should not be a number.");


    jQuery.validator.addMethod('strongPassword', function (value, element) {
    return this.optional(element) || 
           /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/.test(value);
}, "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.");


    jQuery.validator.addMethod('indianPhone', function (value, element) {
        return /^[6-9]\d{9}$/.test(value);
    }, "Please enter a valid Indian phone number starting with 6, 7, 8, or 9.");

    // Form validation rules and messages
    $('#myform').validate({
        rules: {
            name: {
                required: true,
                lettersonly: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true,
                customEmail: true,
                remote: {     //built in function in jquery  
                    url: "check_email.php", 
                    type: "POST",
                    data: {
                        username: function() {  // input for check_email.php 
                            return $("#email").val();
                        }
                    }
                }
            },
            phone_no: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
                indianPhone: true
                
            },
            pass: {
                required: true,
                minlength: 8,
                strongPassword: true,
                all: true
            },
            conpass: {
                required: true,
                equalTo: "#pass"
            }
        },
        messages: {
            name: {
                required: "Please enter your full name",
                lettersonly: "Name must be in alphabets only"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                customEmail: "Please enter a valid email address with Gmail, Yahoo,  or mca.ajce.in domain, and the first character should not be a number.",
                remote:"email already exist! , Try another email"


            },
            phone_no: {
                required: "Phone number is required",
                digits: "Please enter only numbers",
                minlength: "Invalid phone number",
                maxlength: "Invalid phone number",
                indianPhone: "Please enter a valid phone number starting with 6, 7, 8, or 9."
                
            },
            pass: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
                strongPassword: "Password must contain uppercase, lowercase, digit, and special character.",

                all: "Spaces or invalid characters are not allowed"
               
            },
            conpass: {
                required: "Please re-enter the password",
                equalTo: "Password and Confirm Password fields do not match"
            }
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element); // Places error messages after the input field
        }
    });
});

</script>
  </body>
</html>