<?php
include("connect.php");

if (!empty($_SESSION["email"])) {
    $email = $_SESSION["email"];

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

$alertMessage = ""; // To store JavaScript alerts
if(!empty($service_id)){
  $sql="SELECT price FROM tbl_services WHERE service_id=$service_id";
  $result=$conn->query($sql);
  if($result->num_rows>0){
    $row=$result->fetch_assoc();
  }
}

// Function to get booked times for a specific date
function getBookedAppointmentTimes($conn, $date) {
  $bookedTimes = array();
  $stmt = $conn->prepare("SELECT appointment_time FROM tbl_appointments WHERE appointment_date = ? AND status != 'Cancelled'");
  $stmt->bind_param("s", $date);
  $stmt->execute();
  $result = $stmt->get_result();
  
  while($row = $result->fetch_assoc()) {
    $bookedTimes[] = $row['appointment_time'];
  }
  
  $stmt->close();
  return $bookedTimes;
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
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="userindex.php">Beauty<small>Blend</small></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
        aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="userindex.php" class="nav-link">Home</a></li>


          <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>


          <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="shop.php" id="dropdown04" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Products</a>
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

          ?>
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
                <p>"Experience luxury and transformation at our beauty salon, where expert care unveils your radiant
                  glow."</p>
              </div>
            </div>
            <div class="col-md-4 d-flex ftco-animate">
              <div class="icon"><span class="icon-my_location"></span></div>
              <div class="text">
                <h3>Hill top Street</h3>
                <p> Nilambur,<br>Malappuram,<br>Kerala,<br>India</p>
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

          <form action="#" method="POST" id="appointment-form" class="appointment-form">
          <input type="hidden" id="service_id" name="service_id" value="<?php echo $service_id; ?>">
          <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
            <div class="d-md-flex">
              <div class="form-group">
                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Full Name">
              </div>
            </div>
            <div class="d-md-flex">
              <div class="form-group">
                <div class="input-wrap">
                  <!-- <div class="icon"><span class="ion-md-calendar"></span></div> -->
                  <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                    placeholder="Date">
                </div>
              </div>
              <div class="form-group ml-md-4">
                <div class="input-wrap">
                  <!-- <div class="icon"><span class="ion-ios-clock"></span></div>
                <input type="text" name="appointment_time" class="form-control appointment_time" placeholder="Time"> -->

                  <select id="appointment_time" name="appointment_time" required>
                    <option value="">Select Time</option>
                    <option value="04:00 AM">04:00 AM</option>
                    <option value="05:00 AM">05:00 AM</option>
                    <option value="06:00 AM">06:00 AM</option>
                    <option value="07:00 AM">07:00 AM</option>
                    <option value="08:00 AM">08:00 AM</option>
                    <option value="09:00 AM">09:00 AM</option>
                    <option value="10:00 AM">10:00 AM</option>
                    <option value="11:00 AM">11:00 AM</option>
                    <option value="12:00 PM">12:00 PM</option>
                    <option value="01:00 PM">01:00 PM</option>
                    <option value="02:00 PM">02:00 PM</option>
                    <option value="03:00 PM">03:00 PM</option>
                    <option value="04:00 PM">04:00 PM</option>
                    <option value="05:00 PM">05:00 PM</option>
                    <option value="06:00 PM">06:00 PM</option>
                    <option value="07:00 PM">07:00 PM</option>
                    <option value="08:00 PM">08:00 PM</option>
                    <option value="09:00 PM">09:00 PM</option>
                  </select>

                </div>
              </div>
              <div class="form-group ml-md-4">
                <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
              </div>
            </div>
            <div class="d-md-flex">
              <div class="form-group">
                <textarea name="message" id="message" cols="30" rows="2" class="form-control" placeholder="Message"></textarea>
              </div>
              <div class="form-group ml-md-4">
                <!-- <input type="submit" value="Appointment" class="btn btn-white py-3 px-4"> -->
                <input type="hidden" id="appointment_amount" value="<?php echo $row['price'];?>"> <!-- Example amount -->
        <button type="button" id="rzp-button">Pay & Book Appointment</button>
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
            <p>We offers a relaxing atmosphere with professional services, including haircuts, facials, and manicures.
              The salon is known for its friendly staff, quality treatments, and attention to detail.</p>
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
                <li><span class="icon icon-map-marker"></span><span class="text"> Hill top
                    Street<br>Nilambur,<br>Malappuram,<br>Kerala,<br>India</span></li>
                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+2 392 3929 210</span></a></li>
                <li><a href="#"><span class="icon icon-envelope"></span><span
                      class="text">info@yourdomain.com</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">




          <!-- loader -->
          <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
              <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
              <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00" />
            </svg></div>
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
          <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
          <script src="js/google-map.js"></script>
          <script src="js/main.js"></script>
          <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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

    let oneYearFromToday = new Date();
    oneYearFromToday.setFullYear(today.getFullYear() + 1); // Set to exactly 1 year from today

    return selectedDate >= today && selectedDate <= oneYearFromToday;
}, "Please select a date between today and 1 year from now.");


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
                    validDate: "Please select a date between today and 1 year from now"
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
                }
              });

              // Prevent selecting past dates in the input field
              let today = new Date().toISOString().split("T")[0];
              $("#appointment_date").attr("min", today);
              
              // Handle date change to fetch booked time slots
              $("#appointment_date").on('change', function() {
                var selectedDate = $(this).val();
                if(selectedDate) {
                  // Make AJAX call to get booked times
                  $.ajax({
                    type: "POST",
                    url: "get_booked_times.php",
                    data: { date: selectedDate },
                    dataType: "json",
                    success: function(response) {
                      // Enable all time slots first
                      $("#appointment_time option").prop("disabled", false);
                      
                      // Disable booked time slots
                      if(response.booked_times) {
                        $.each(response.booked_times, function(index, time) {
                          $("#appointment_time option[value='" + time + "']").prop("disabled", true);
                        });
                      }
                      
                      // If the currently selected time is now disabled, reset selection
                      if($("#appointment_time").val() && $("#appointment_time option:selected").prop("disabled")) {
                        $("#appointment_time").val("");
                      }
                    },
                    error: function(xhr, status, error) {
                      console.error("Error fetching booked times:", error);
                    }
                  });
                }
              });
            });
         
            document.getElementById('rzp-button').onclick = function (e) {
                e.preventDefault();
                var form = document.getElementById('appointment-form');
                if (!form.checkValidity()) {
                    alert('Please fill all required fields');
                    return;
                }

                var userId = document.getElementById('user_id').value;
                var serviceId = document.getElementById('service_id').value;
                var fullName = document.getElementById('full_name').value;
                var appointmentDate = document.getElementById('appointment_date').value;
                var appointmentTime = document.getElementById('appointment_time').value;
                var phone = document.getElementById('phone').value;
                var message = document.getElementById('message').value;
                var eamount = document.getElementById('appointment_amount').value * 100; // Convert INR to paise
                var amount=eamount/2;

                // Check if the selected time slot is still available
                $.ajax({
                    type: "POST",
                    url: "check_time_availability.php",
                    data: { 
                        date: appointmentDate,
                        time: appointmentTime
                    },
                    dataType: "json",
                    success: function(response) {
                        if(response.available) {
                            // Proceed with payment if time slot is available
                            initiatePayment(userId, serviceId, fullName, appointmentDate, appointmentTime, phone, message, amount);
                        } else {
                            Swal.fire({
                                title: "Time Slot Unavailable",
                                text: "Sorry, this time slot has just been booked by someone else. Please choose another time.",
                                icon: "warning",
                                confirmButtonText: "OK"
                            });
                            
                            // Refresh available time slots
                            $("#appointment_date").trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error checking time availability:", error);
                        Swal.fire({
                            title: "Error",
                            text: "Could not verify time slot availability. Please try again.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            };
            
            function initiatePayment(userId, serviceId, fullName, appointmentDate, appointmentTime, phone, message, amount) {
                var options = {
                    "key": "rzp_test_s8u2UQ54kE7TBA",
                    "amount": amount,
                    "currency": "INR",
                    "name": "Serenity Styles",
                    "description": "Appointment Booking",
                    "handler": function (response) {
                        $.ajax({
                            type: "POST",
                            url: "booknow_process.php",
                            data: {
                                user_id: userId,
                                service_id: serviceId,
                                name: fullName,
                                appointment_date: appointmentDate,
                                appointment_time: appointmentTime,
                                phone: phone,
                                message: message,
                                amount: amount / 100, // Convert back to INR
                                payment_id: response.razorpay_payment_id,
                                status: 'Pending'
                            },
                            success: function (result) {
                                console.log(result);
                                if (result.trim() === "success") {
                                    Swal.fire({
                                        title: "Payment Successful!",
                                        text: "Wait for Appointment Confirmation.",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        window.location.href = "booknow.php";
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Server error.Try again!",
                                        icon: "error",
                                        confirmButtonText: "OK"
                                    });
                                }
                            }
                        });
                    },
                    "prefill": {
                        "name": fullName,
                        "contact": phone
                    },
                    "theme": {
                        "color": "#c49b63"
                    }
                };
                
                var rzp1 = new Razorpay(options);
                rzp1.open();
                rzp1.on('payment.failed', function (response) {
                    alert("Payment Failed: " + response.error.description);
                });
            }
          </script>
          
<?php
// Display the alert after the page loads
echo $alertMessage;
?>
</body>

</html>