<?php
include('connect.php');
$user_id = $_SESSION['user_id'];
$sql = "SELECT SUM(unit_price) AS total FROM tbl_cart WHERE status='Active' AND user_id=$user_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$cart_ids = json_decode($_POST['cart_ids'], true); // Decode JSON back to array


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>BeautyBlend - Checkout</title>
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
          <li class="nav-item"><a href="booknow.php" class="nav-link">Book Now</a></li>
          <li class="nav-item cart"><a href="cart.php" class="nav-link"><span
                class="icon icon-shopping_cart"></span><span
                class="bag d-flex justify-content-center align-items-center"><small>1</small></span></a></li>
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
            <h1 class="mb-3 mt-5 bread">Checkout</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="userindex.php">Home</a></span> <span>Checkout</span></p>
          </div>

        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 ftco-animate">

          <form id="checkout-form" class="billing-form ftco-bg-dark p-3 p-md-5">
            <h3 class="mb-4 billing-heading">Billing Details</h3>
            <div class="row align-items-end">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="firstname">Full Name</label>
                  <input type="text" name="firstname" id="firstname" class="form-control" placeholder="" required>
                </div>
              </div>


              <div class="w-100"></div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="country">State</label>
                  <div class="select-wrap">
                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                    <select name="state" id="state" class="form-control" required>
                      <option value="">Select State</option>
                      <option value="Kerala">Kerala</option>
                      <option value="Tamil Nadu">Tamil Nadu</option>
                      <option value="Karnadaka">Karnadaka</option>
                      <option value="Goa">Goa</option>
                      <option value="Gujarath">Gujarath</option>
                      <option value="Maharashtra">Maharashtra</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="w-100"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="district">District</label>
                  <input type="text" name="district" id="district" class="form-control"
                    placeholder="Enter your district" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="address2">Address</label>
                  <input type="text" name="address" id="address" class="form-control"
                    placeholder="Appartment, suite, unit etc: (optional)">
                </div>
              </div>
              <div class="w-100"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="towncity">Town / City</label>
                  <input type="text" name="towncity" id="towncity" class="form-control" placeholder="" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="postcodezip">Postcode / ZIP *</label>
                  <input type="text" name="postcodezip" id="postcodezip" class="form-control" placeholder="" required>
                </div>
              </div>
              <div class="w-100"></div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" name="phone" id="phone" class="form-control" placeholder="" required>
                </div>
              </div>


            </div>
            <input type="hidden" name="cart_ids" id="cart_ids"
              value="<?= htmlspecialchars(json_encode($cart_ids)); ?>">
          </form><!-- END -->



          <div class="row mt-5 pt-3 d-flex">
            <div class="col-md-6 d-flex">
              <div class="cart-detail cart-total ftco-bg-dark p-3 p-md-4">
                <h3 class="billing-heading mb-4">Cart Total</h3>
                <p class="d-flex">
                  <span>Subtotal</span>Rs.&nbsp
                  <span><?php echo $row['total']; ?></span>
                </p>


                <hr>
                <p class="d-flex total-price">
                  <span>Total</span>Rs.&nbsp
                  <span id="total-amount"><?php echo $row['total']; ?></span>
                </p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="cart-detail ftco-bg-dark p-3 p-md-4">
                <h3 class="billing-heading mb-4">Payment Method</h3>

                <div class="form-group">
                  <div class="col-md-12">
                    <div class="radio">
                      <label><input type="radio" name="payment" value="razorpay" class="mr-2" checked> Razorpay
                        (Credit/Debit Card, UPI, etc.)</label>
                    </div>
                  </div>
                </div>

                <p><button id="rzp-button" class="btn btn-primary py-3 px-4">Place an order</button></p>
              </div>
            </div>
          </div>
        </div> <!-- .col-md-8 -->




        <div class="col-xl-4 sidebar ftco-animate">
          <div class="sidebar-box">
            <form action="#" class="search-form">
              <div class="form-group">


            </form>
          </div>
        </div>

      </div>
    </div>
  </section> <!-- .section -->

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
                <li><a href="#"><span class="icon icon-phone"></span><span class="text">8590918598</span></a></li>
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
              <circle class="path-bg" cx="24" cy="24" r="22
  " fill="none" stroke-width="4" stroke="#eeeeee" />
              <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00" />
            </svg></div>


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
          <!-- Razorpay JS SDK -->
          <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

          <script>
            $(document).ready(function () {
              
              var quantitiy = 0;
              $('.quantity-right-plus').click(function (e) {

                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());

                // If is not undefined

                $('#quantity').val(quantity + 1);


                // Increment

              });

              $('.quantity-left-minus').click(function (e) {
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());

                // If is not undefined

                // Increment
                if (quantity > 0) {
                  $('#quantity').val(quantity - 1);
                }
              });


              jQuery.validator.addMethod('lettersonly', function (value, element) {
                return /^[^-\s][a-zA-Z_\s-]+$/.test(value);
              }, "Please use letters only.");

              jQuery.validator.addMethod('indianPhone', function (value, element) {
                return /^[6-9]\d{9}$/.test(value);
              }, "Please enter a valid Indian phone number starting with 6, 7, 8, or 9.");

              $("#checkout-form").validate({
                rules: {
                  firstname: {
                    required: true,
                    lettersonly: true,
                    minlength: 3
                  },
                  state: {
                    required: true
                  },
                  district: {
                    required: true,
                    lettersonly: true,
                    minlength: 3
                  },
                  address: {
                    required: true,
                    minlength: 3

                  },
                  towncity: {
                    required: true,
                    lettersonly: true,
                    minlength: 3
                  },
                  postcodezip: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                  },
                  phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10,
                    indianPhone: true
                  }
                },
                messages: {
                  firstname: {
                    required: "Please enter your full name",
                    lettersonly: "Name must be in alphabets only"
                  },
                  state: {
                    required: "Please select a state"
                  },
                  district: {
                    required: "Please enter your district",
                    lettersonly: "District must be in alphabets only"
                  },
                  address: {
                    required: "Please enter your address"
                  },
                  towncity: {
                    required: "Please enter your city",
                    lettersonly: "City must be in alphabets only"
                  },
                  postcodezip: {
                    required: "Please enter your ZIP code",
                    digits: "Only numbers are allowed",
                    minlength: "ZIP code must be exactly 6 digits",
                    maxlength: "ZIP code must be exactly 6 digits"
                  },
                  phone: {
                    required: "Phone number is required",
                    digits: "Please enter only numbers",
                    minlength: "Invalid phone number",
                    maxlength: "Invalid phone number",
                    indianPhone: "Please enter a valid phone number starting with 6, 7, 8, or 9."
                  }
                },
                errorPlacement: function (error, element) {
                  error.insertAfter(element); // Places the error message right below the input field
                  error.css("color", "red"); // Makes errors red for better visibility
                },
                submitHandler: function (form) {
                  form.submit();
                }


              });
            });


            // Razorpay Integration
            document.getElementById('rzp-button').onclick = function (e) {
              e.preventDefault();
              var form = document.getElementById('checkout-form');
              if (!form.checkValidity()) {
                alert('Please fill all required fields');
                return false;
              }

              var fullName = document.getElementById('firstname').value;
              var state = document.getElementById('state').value;
              var district = document.getElementById('district').value;
              var city = document.getElementById('towncity').value;
              var address = document.getElementById('address').value;
              var zip = document.getElementById('postcodezip').value;
              var phone = document.getElementById('phone').value;
              var totalAmountText = document.getElementById('total-amount').innerText.trim(); // Remove spaces

// Extract the numeric part, leaving the decimal point intact
var cleanedAmount = totalAmountText.replace(/[^\d.]/g, '');

// Ensure correct conversion (1 Rs = 100 paise)
var amount = parseFloat(cleanedAmount) * 100; // Multiply by 100 to convert rupees to paise

var eamount = 0;
if (!isNaN(amount)) {
    eamount = Math.round(amount); // Round the result to get whole paise
} else {
    console.error("Invalid amount extracted:", cleanedAmount);
}

              var cart_ids = document.getElementById('cart_ids').value;


              var options = {
                "key": "rzp_test_s8u2UQ54kE7TBA",
                "amount": eamount,
                "currency": "INR",
                "name": "Serenity Styles",
                "description": "Beauty Products Purchase",
                "image": "images/logo.png",
                "handler": function (response) {
                  var payment_id = response.razorpay_payment_id;

                  // Send data to PHP for inserting into the database
                  $.ajax({
                    type: "POST",
                    url: "checkout_process.php",
                    data: {
                      name: fullName,
                      state: state,
                      district: district,
                      address: address,
                      city: city,
                      zip: zip,
                      phone: phone,
                      amount: eamount / 100, // Convert back to INR
                      payment_id: payment_id,
                      cart_ids: cart_ids
                    },
                    success: function (result) {
                      if (result.trim() === "success") {
                        alert("Payment Successful! Order Placed.");
                        window.location.href = "order.php";
                      } else {
                        alert("Database Error: " + result);
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

              return false;
            };






          </script>


</body>

</html>