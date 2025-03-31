<?php
include('connect.php');

if (!empty($_SESSION["email"])) {
    $email = $_SESSION["email"];
    $sql = "SELECT u.name FROM tbl_user AS u JOIN tbl_login AS l ON u.user_id = l.user_id WHERE l.email = '$email'";
  
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
    }
  }

// Fetch user's appointments
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual session handling
$today = date('Y-m-d'); // Define $today before using it
// Fetch upcoming appointments
$upcoming_query = "SELECT a.appointment_id, a.date, a.time, a.status, s.service_name 
                   FROM tbl_appointment a
                   JOIN tbl_services s ON a.service_id = s.service_id
                   WHERE a.user_id = ? AND a.date >= ? AND a.status != 'Cancelled'
                   ORDER BY a.date ASC";

$stmt = $conn->prepare($upcoming_query);
$stmt->bind_param("is", $user_id, $today); // "i" for integer, "s" for string (date)
$stmt->execute();
$upcoming_result = $stmt->get_result();


// Fetch past appointments
$past_query = "SELECT a.appointment_id, a.date, a.time, a.status, s.service_name 
               FROM tbl_appointment a
               JOIN tbl_services s ON a.service_id = s.service_id
               WHERE a.user_id = ? AND (a.date < ? OR a.status = 'Cancelled')
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
                    <li class="nav-item"><a href="userindex.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="review_view.php" class="nav-link">Review</a></li>
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

    <a class="dropdown-item" href="viewappointments.php?user_id=<?php echo $user_id; ?>">View
      Appointments</a>
    <a class="dropdown-item" href="order.php?user_id=<?php echo $user_id; ?>">View
      Orders</a>
    <a class="dropdown-item" href="logout.php?user_id=<?php echo $user_id; ?>">Log Out</a>
  </div>

</li>
<?php }

?>
<?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'user') { ?>

  <li class="nav-item"><a href="cart.php" class="nav-link">Cart</a></li>
<?php }

?>
                   
                    
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
                        <p class="breadcrumbs"><span class="mr-2"><a href="userindex.php">Home</a></span> <span>Appointments</span></p>
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
                        <div id="upcoming-appointments-container">
                            <?php if ($upcoming_result->num_rows > 0): ?>
                                <?php while($appointment = $upcoming_result->fetch_assoc()): ?>
                                    <div class="appointment-card p-4 mb-3" id="appointment-<?php echo $appointment['appointment_id']; ?>" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">
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
                                            <a href="reschedule_appointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary py-2 px-3">
    Reschedule
</a>
                                                <button class="btn btn-danger py-2 px-3 cancel-appointment" 
                                                        data-id="<?php echo $appointment['appointment_id']; ?>">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No upcoming appointments</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- <div class="text-center mt-4">
                            <a href="book-appointment.php" class="btn btn-primary py-3 px-4">Book New Appointment</a>
                        </div> -->
                    </div>

                    <!-- Past Appointments -->
                    <div class="billing-form ftco-bg-dark p-3 p-md-5">
                        <h3 class="mb-4 billing-heading">Past Appointments</h3>
                        <div id="past-appointments-container">
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
                                            <div class="col-md-3">
                                                <strong>Status</strong>
                                                <p><?php echo htmlspecialchars($appointment['status']); ?></p>
                                            </div>
                                            
                                            <div class="col-md-3 text-md-right">
                                                <?php if (!isset($appointment['review_id']) && $appointment['status'] !== 'Cancelled'): ?>
                                                    <a href="review.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary py-2 px-3">Write Review</a>
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
    
    <script>
    $(document).ready(function() {
        // Handle appointment cancellation
        $('.cancel-appointment').on('click', function() {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                var appointmentId = $(this).data('id');
                var appointmentCard = $('#appointment-' + appointmentId);
                
                // Send AJAX request to cancel_appointment.php
                $.ajax({
                    url: 'cancel_appointment.php',
                    type: 'GET',
                    data: { id: appointmentId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Remove the appointment card from upcoming appointments
                            appointmentCard.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Check if there are no more upcoming appointments
                                if ($('#upcoming-appointments-container .appointment-card').length === 0) {
                                    $('#upcoming-appointments-container').html('<p>No upcoming appointments</p>');
                                }
                                
                                // Optionally, add the cancelled appointment to past appointments
                                // This would require modifying the cancel_appointment.php to return the appointment data
                                if (response.appointment) {
                                    var cancelledAppointment = 
                                        '<div class="appointment-card p-4 mb-3" style="border: 1px solid rgba(255,255,255,0.1); border-radius: 5px;">' +
                                            '<div class="row">' +
                                                '<div class="col-md-3">' +
                                                    '<strong>Date & Time</strong>' +
                                                    '<p>' + response.appointment.date + '<br>' +
                                                    response.appointment.time + '</p>' +
                                                '</div>' +
                                                '<div class="col-md-3">' +
                                                    '<strong>Service</strong>' +
                                                    '<p>' + response.appointment.service_name + '</p>' +
                                                '</div>' +
                                                '<div class="col-md-3">' +
                                                    '<strong>Status</strong>' +
                                                    '<p>Cancelled</p>' +
                                                '</div>' +
                                                '<div class="col-md-3 text-md-right"></div>' +
                                            '</div>' +
                                        '</div>';
                                    
                                    $('#past-appointments-container').prepend(cancelledAppointment);
                                    
                                    // If there was a "No past appointments" message, remove it
                                    if ($('#past-appointments-container p').text() === 'No past appointments') {
                                        $('#past-appointments-container p').remove();
                                    }
                                }
                                
                                alert('Appointment cancelled successfully.');
                            });
                        } else {
                            alert(response.message || 'Error cancelling appointment. Please try again.');
                        }
                    },
                    error: function() {
                        alert('Error connecting to the server. Please try again.');
                    }
                });
            }
        });
    });
    </script>
</body>
</html>