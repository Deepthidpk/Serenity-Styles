<?php
include('connect.php');

// Fetch user's appointments
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual session handling
$today = date('Y-m-d'); // Define $today before using it
// Fetch upcoming appointments
$upcoming_query = "SELECT a.appointment_id, a.date, a.time, a.status, s.service_name 
                   FROM tbl_appointment a
                   JOIN tbl_services s ON a.service_id = s.service_id
                   WHERE a.user_id = ? AND a.date >= ?
                   ORDER BY a.date ASC";

$stmt = $conn->prepare($upcoming_query);
$stmt->bind_param("is", $user_id, $today); // "i" for integer, "s" for string (date)
$stmt->execute();
$upcoming_result = $stmt->get_result();


// Fetch past appointments
$past_query = "SELECT a.appointment_id, a.date, a.time, a.status, s.service_name 
               FROM tbl_appointment a
               JOIN tbl_services s ON a.service_id = s.service_id
               WHERE a.user_id = ? AND a.date < ?
               ORDER BY a.date DESC";

$stmt = $conn->prepare($past_query);
$stmt->bind_param("is", $user_id, $today); // "i" for integer (user_id), "s" for string (date)
$stmt->execute();
$past_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BeautyBlend - My Appointments</title>
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
            <a class="navbar-brand" href="userindex.html">Beauty<small>Blend</small></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="userindex.html" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="services.html" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
                    <li class="nav-item active"><a href="appointments.php" class="nav-link">Appointments</a></li>
                    <li class="nav-item"><a href="profile.php" class="nav-link">Profile</a></li>
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
                        <h1 class="mb-3 mt-5 bread">My Appointments</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="userindex.html">Home</a></span> <span>Appointments</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointments Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <!-- Upcoming Appointments -->
                    <div class="billing-form ftco-bg-dark p-3 p-md-5 mb-5">
                        <h3 class="mb-4 billing-heading">Upcoming Appointments</h3>
                        <?php if ($upcoming_result->num_rows > 0): ?>
                            <?php while($appointment = $upcoming_result->fetch_assoc()): ?>
                                <div class="appointment-card p-4 mb-3" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Date & Time</strong>
                                            <p><?php echo date('M d, Y', strtotime($appointment['date'])); ?><br>
                                               <?php echo date('h:i A', strtotime($appointment['time'])); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Service</strong>
                                            <p><?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                        </div>
                                        
                                        <div class="col-md-3 text-md-right">
                                            <a href="reschedule-appointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary py-2 px-3">Reschedule</a>
                                            <a href="cancel-appointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-danger py-2 px-3">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No upcoming appointments</p>
                        <?php endif; ?>
                        
                        <div class="text-center mt-4">
                            <a href="book-appointment.php" class="btn btn-primary py-3 px-4">Book New Appointment</a>
                        </div>
                    </div>

                    <!-- Past Appointments -->
                    <div class="billing-form ftco-bg-dark p-3 p-md-5">
                        <h3 class="mb-4 billing-heading">Past Appointments</h3>
                        <?php if ($past_result->num_rows > 0): ?>
                            <?php while($appointment = $past_result->fetch_assoc()): ?>
                                <div class="appointment-card p-4 mb-3" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Date & Time</strong>
                                            <p><?php echo date('M d, Y', strtotime($appointment['date'])); ?><br>
                                               <?php echo date('h:i A', strtotime($appointment['time'])); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Service</strong>
                                            <p><?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                        </div>
                                        
                                        <div class="col-md-3 text-md-right">
                                            <?php if (!isset($appointment['review_id'])): ?>
                                                <a href="write-review.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary py-2 px-3">Write Review</a>
                                            <?php endif; ?>
                                           
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No past appointments</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

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
</body>
</html>