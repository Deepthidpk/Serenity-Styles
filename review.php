<?php

include 'connect.php'; // Database connection file


if (!empty($_SESSION["email"])) {
	$email = $_SESSION["email"];
	$sql = "SELECT u.name FROM tbl_user AS u JOIN tbl_login AS l ON u.user_id = l.user_id WHERE l.email = '$email'";

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
	}
}




// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must be logged in to submit a review.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = trim($_POST['comment']); // Sanitize input
    $rating = intval($_POST['rating']); // Ensure rating is an integer
    $user_id = $_SESSION['user_id']; // Get user ID from session

    // Validate input
    if (empty($comment) || $rating < 1 || $rating > 5) {
        echo "<script>alert('Invalid review or rating.'); window.history.back();</script>";
        exit();
    }

    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO tbl_review (user_id, comment, rating, review_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $user_id, $comment, $rating);

    if ($stmt->execute()) {
        echo "<script>alert('Review submitted successfully!'); window.location.href='review.php';</script>";
    } else {
        echo "<script>alert('Failed to submit review.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>BeautyBlend - Write Review</title>
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
    
    <!-- Additional CSS for star rating -->
    <style>
        .star-rating {
            font-size: 24px;
            cursor: pointer;
            display: inline-block;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            color: #ccc;
            float: right;
            padding: 0 2px;
            transition: all 0.2s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f9d71c;
        }
        .appointment-summary {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        textarea.form-control {
            min-height: 150px;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        textarea.form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .photo-upload {
            margin-top: 20px;
        }
        .photo-upload label {
            display: block;
            margin-bottom: 10px;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 8px 16px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            transition: all 0.3s;
        }
        .custom-file-upload:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .uploaded-photos {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .uploaded-photo {
            width: 100px;
            height: 100px;
            margin: 5px;
            position: relative;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="userindex.php">Beauty<small>Blend</small></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="userindex.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="shop.php" class="nav-link">Products</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="review.php" class="nav-link">Review</a></li>



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

    <!-- Header Section -->
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(images/coverpage1.jpg);" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread"> Review & Rating</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="userindex.php">Home</a></span> <span class="mr-2"><a href="appointments.php">Appointments</a></span> <span>Write Review</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Form Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="billing-form ftco-bg-dark p-3 p-md-5">
                        <h3 class="mb-4 billing-heading">Share Your Experience</h3>
                        
                        <!-- Appointment Summary -->
                        <!-- <div class="appointment-summary mb-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Service</strong><br>
                                    <?php echo htmlspecialchars($appointment['service_name']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Stylist</strong><br>
                                    <?php echo htmlspecialchars($appointment['staff_name']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Date & Time</strong><br>
                                    <?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?><br>
                                    <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></p>
                                </div>
                            </div>
                        </div> -->
                        
                        <!-- Review Form -->
                        <form action="review.php" method="POST">
    <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" class="form-control" required></textarea>
    </div>

    <!-- Star Rating System -->
    <div class="form-group">
        <label for="rating">Rating:</label>
        <div class="star-rating">
            <input type="radio" id="star5" name="rating" value="5">
            <label for="star5" class="star">&#9733;</label>
            
            <input type="radio" id="star4" name="rating" value="4">
            <label for="star4" class="star">&#9733;</label>
            
            <input type="radio" id="star3" name="rating" value="3">
            <label for="star3" class="star">&#9733;</label>
            
            <input type="radio" id="star2" name="rating" value="2">
            <label for="star2" class="star">&#9733;</label>
            
            <input type="radio" id="star1" name="rating" value="1">
            <label for="star1" class="star">&#9733;</label>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="text-center mt-5">
        <button type="submit" class="btn btn-primary py-3 px-5">Submit Review</button>
        <a href="review.php" class="btn btn-secondary py-3 px-5 ml-2">Cancel</a>
    </div>
</form>

<!-- CSS for Star Rating -->
<style>
    .star-rating {
        direction: rtl; /* Right to left for proper alignment */
        display: flex;
        justify-content: start;
        font-size: 30px;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: gold;
    }
</style>


                    </div>
                </div>
            </div>
        </div>
    </section>

   
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>

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
    
    <!-- Custom Script for Photo Upload Preview -->
    <script>
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.querySelector('.uploaded-photos');
        previewContainer.innerHTML = '';
        
        for (let i = 0; i < Math.min(files.length, 4); i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const photoDiv = document.createElement('div');
                photoDiv.className = 'uploaded-photo';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                
                photoDiv.appendChild(img);
                previewContainer.appendChild(photoDiv);
            }
            
            reader.readAsDataURL(file);
        }
    });



    </script>
    <script>

// Star rating toggle functionality
let currentRating = 0;

function toggleStar(starNumber) {
    const starInputs = document.querySelectorAll('.star-rating input');
    const starLabels = document.querySelectorAll('.star-rating label');
    
    // If clicking on the currently selected star, deselect all stars
    if (currentRating === starNumber) {
        currentRating = 0;
        starInputs.forEach(input => {
            input.checked = false;
        });
        
        // Reset all stars to default color
        starLabels.forEach(label => {
            label.style.color = '#ccc';
        });
    } else {
        // Set new rating
        currentRating = starNumber;
        
        // Update the star colors
        starLabels.forEach((label, index) => {
            const starValue = 5 - index;
            if (starValue <= starNumber) {
                label.style.color = '#f9d71c'; // Selected color
            } else {
                label.style.color = '#ccc'; // Default color
            }
        });
        
        // Check the appropriate radio button
        document.getElementById('star' + starNumber).checked = true;
    }
}
</script>
</body>
</html>