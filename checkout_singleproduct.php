<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1; // Default to 1 if not provided

    if (!$product_id || !is_numeric($quantity) || $quantity < 1) {
        echo "Invalid request.";
        exit;
    }

    // Fetch product details
    $sql = "SELECT * FROM tbl_products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    $total_price = $product['price'] * $quantity;
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
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
                        <div class="billing-form ftco-bg-dark p-3 p-md-5">
                            <h3 class="mb-4 billing-heading">Checkout Summary</h3>
                            <div class="row align-items-end mb-5">
                                <div class="col-md-6">
                                    <p><strong>Product:</strong> <?php echo htmlspecialchars($product['product_name']); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Total:</strong> Rs. <?php echo number_format($total_price, 2); ?></p>
                                </div>
                            </div>
                            
                            <h3 class="mb-4 billing-heading">Billing Details</h3>
                            <form id="checkout-form" class="billing-form">
                                <div class="row align-items-end">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" id="name" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="w-100"></div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <div class="select-wrap">
                                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                                <select name="state" id="state" class="form-control" required>
                                                    <option value="">Select State</option>
                                                    <option value="Kerala">Kerala</option>
                                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                                    <option value="Karnataka">Karnataka</option>
                                                    <option value="Goa">Goa</option>
                                                    <option value="Gujarat">Gujarat</option>
                                                    <option value="Maharashtra">Maharashtra</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-100"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="district">District</label>
                                            <input type="text" id="district" class="form-control" placeholder="Enter your district" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">City / Town</label>
                                            <input type="text" id="city" class="form-control" placeholder="Enter your city" required>
                                        </div>
                                    </div>
                                    
                                    <div class="w-100"></div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea id="address" class="form-control" placeholder="House no., Street name, Locality" required></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="w-100"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pincode">Pincode / ZIP</label>
                                            <input type="text" id="pincode" class="form-control" placeholder="6-digit pincode" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" id="phone" class="form-control" placeholder="10-digit mobile number" required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="row mt-5 pt-3 d-flex">
                                <div class="col-md-6 d-flex">
                                    <div class="cart-detail cart-total ftco-bg-dark p-3 p-md-4">
                                        <h3 class="billing-heading mb-4">Cart Total</h3>
                                        <p class="d-flex">
                                            <span>Subtotal</span>
                                            <span>Rs. <?php echo number_format($total_price, 2); ?></span>
                                        </p>
                                        <hr>
                                        <p class="d-flex total-price">
                                            <span>Total</span>
                                            <span>Rs. <?php echo number_format($total_price, 2); ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="cart-detail ftco-bg-dark p-3 p-md-4">
                                        <h3 class="billing-heading mb-4">Payment Method</h3>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="radio">
                                                    <label><input type="radio" name="payment" class="mr-2" checked> Razorpay (Credit/Debit Card, UPI, etc.)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <p><button type="button" onclick="initiatePayment()" class="btn btn-primary py-3 px-4">Place an order</button></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- .col-md-8 -->
                    
                    <div class="col-xl-4 sidebar ftco-animate">
                        <div class="sidebar-box">
                            <form action="#" class="search-form">
                                <div class="form-group">
                                </div>
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
            </div>
        </footer>

        <!-- loader -->
        <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
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
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
        <script src="js/google-map.js"></script>
        <script src="js/main.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

        <script>
            function initiatePayment() {
                var name = document.getElementById('name').value;
                var email = document.getElementById('email').value;
                var phone = document.getElementById('phone').value;
                var address = document.getElementById('address').value;
                var state = document.getElementById('state').value;
                var district = document.getElementById('district').value;
                var city = document.getElementById('city').value;
                var pincode = document.getElementById('pincode').value;
                
                if (!name || !email || !phone || !address || !state || !district || !city || !pincode) {
                    alert('Please fill all the details.');
                    return;
                }
                
                var options = {
                    "key": "rzp_test_s8u2UQ54kE7TBA", // Replace with your Razorpay Key
                    "amount": <?php echo $total_price * 100; ?>, // Convert to paisa
                    "currency": "INR",
                    "name": "BeautyBlend",
                    "description": "Purchase of <?php echo htmlspecialchars($product['product_name']); ?>",
                    "handler": function (response) {
                        fetch('process_payment.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: new URLSearchParams({
                                user_id: "1", // Replace with actual user session ID
                                product_id: "<?php echo $product_id; ?>",
                                quantity: "<?php echo $quantity; ?>",
                                total_price: "<?php echo $total_price; ?>",
                                payment_id: response.razorpay_payment_id,
                                address: address,
                                state: state,
                                district: district,
                                city: city,
                                pincode: pincode,
                                phone_no: phone
                            })
                        }).then(res => res.text()).then(data => {
                            alert(data);
                            window.location.href = 'order.php';
                        });
                    },
                    "prefill": {
                        "name": name,
                        "email": email,
                        "contact": phone
                    },
                    "theme": {
                        "color": "#c49b63"
                    }
                };
                
                var rzp = new Razorpay(options);
                rzp.open();
            }

            $(document).ready(function() {
                // Form validation
                $("#checkout-form").validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        phone: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 10
                        },
                        address: {
                            required: true,
                            minlength: 5
                        },
                        state: {
                            required: true
                        },
                        district: {
                            required: true
                        },
                        city: {
                            required: true
                        },
                        pincode: {
                            required: true,
                            digits: true,
                            minlength: 6,
                            maxlength: 6
                        }
                    },
                    messages: {
                        phone: {
                            digits: "Please enter only numbers",
                            minlength: "Phone number must be 10 digits",
                            maxlength: "Phone number must be 10 digits"
                        },
                        pincode: {
                            digits: "Please enter only numbers",
                            minlength: "Pincode must be 6 digits",
                            maxlength: "Pincode must be 6 digits"
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.insertAfter(element);
                        error.css("color", "red");
                    }
                });
            });
        </script>
    </body>
    </html>
    <?php
} else {
    echo "Invalid request.";
}
?>