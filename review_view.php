 <?php
include 'connect.php'; // Database connection file

// Fetch all approved reviews
$sql = "SELECT r.review_id, r.user_id, r.comment, r.rating, r.review_date, u.name 
        FROM tbl_review r
        JOIN tbl_user u ON r.user_id = u.user_id
        WHERE r.status = 'Active' 
        ORDER BY r.review_date DESC";
$result = $conn->query($sql);

// Calculate average rating
$avg_query = "SELECT AVG(rating) as avg_rating FROM tbl_review WHERE status = 'Active'";
$avg_result = $conn->query($avg_query);
$avg_row = $avg_result->fetch_assoc();
$average_rating = number_format($avg_row['avg_rating'], 1);

// Count total reviews
$total_reviews = $result->num_rows;
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <title>BeautyBlend - Customer Reviews</title>
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
    
    <!-- Additional CSS for reviews page -->
    <style>
        .star-display {
            color: #f9d71c;
            font-size: 18px;
        }
        .empty-star {
            color: #ccc;
            font-size: 18px;
        }
        .review-card {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            padding: 25px;
            margin-bottom: 30px;
            transition: all 0.3s;
        }
        .review-card:hover {
            background: rgba(0, 0, 0, 0.3);
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
        }
        .reviewer-info {
            display: flex;
            align-items: center;
        }
        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #c49b63;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 15px;
        }
        .review-date {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
        }
        .review-content {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            line-height: 1.6;
        }
        .summary-box {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 5px;
            padding: 30px;
            margin-bottom: 40px;
            text-align: center;
        }
        .summary-box h2 {
            color: #fff;
            margin-bottom: 20px;
        }
        .large-rating {
            font-size: 48px;
            color: #c49b63;
            margin: 15px 0;
            font-weight: 600;
        }
        .rating-stars-large {
            font-size: 28px;
            margin: 10px 0 20px;
        }
        .review-filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .filter-btn {
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #c49b63;
        }
        .write-review-btn {
            background: #c49b63;
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 16px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .write-review-btn:hover {
            background: #a27b43;
            color: #fff;
        }
        .pagination {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }
        .pagination a {
            color: rgba(255, 255, 255, 0.7);
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }
        .pagination a:hover, .pagination a.active {
            background: #c49b63;
            color: #fff;
        }
        .no-reviews {
            text-align: center;
            padding: 50px 0;
            color: rgba(255, 255, 255, 0.7);
        }
        h4{
            font-size: 16px;
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
                    <li class="nav-item active"><a href="all_reviews.php" class="nav-link">Reviews</a></li>
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
                        <h1 class="mb-3 mt-5 bread">Customer Reviews</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="userindex.php">Home</a></span> <span>Reviews</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <!-- Rating Summary -->
                    <div class="summary-box">
                        <h2>Our Customer Ratings</h2>
                        <div class="large-rating"><?php echo $average_rating; ?></div>
                        <div class="rating-stars-large">
                            <?php
                            $full_stars = floor($average_rating);
                            $half_star = $average_rating - $full_stars > 0.4;
                            $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                            
                            for ($i = 0; $i < $full_stars; $i++) {
                                echo '<span class="star-display">&#9733;</span>';
                            }
                            
                            if ($half_star) {
                                echo '<span class="star-display">&#9734;</span>';
                            }
                            
                            for ($i = 0; $i < $empty_stars; $i++) {
                                echo '<span class="empty-star">&#9733;</span>';
                            }
                            ?>
                        </div>
                        <p>Based on <?php echo $total_reviews; ?> reviews</p>
                        <a href="review.php" class="write-review-btn">Write a Review</a>
                    </div>
                    
                    <!-- Filter Options -->
                    <div class="review-filters">
                        <div>
                            <button class="filter-btn active" data-rating="all">All</button>
                            <button class="filter-btn" data-rating="5">5 Star</button>
                            <button class="filter-btn" data-rating="4">4 Star</button>
                            <button class="filter-btn" data-rating="3">3 Star</button>
                            <button class="filter-btn" data-rating="2">2 Star</button>
                            <button class="filter-btn" data-rating="1">1 Star</button>
                        </div>
                        <div>
                            <select id="sort-select" class="form-control" style="background: rgb(18, 17, 17); color:  #c49b63; border: none;">
                                <option value="newest" style="background: rgb(18, 17, 17); color:  #c49b63; border: none;">Newest First</option>
                                <option value="oldest" style="background: rgb(18, 17, 17); color:  #c49b63; border: none;">Oldest First</option>
                                <option value="highest" style="background: rgb(18, 17, 17); color:  #c49b63; border: none;">Highest Rating</option>
                                <option value="lowest" style="background: rgb(18, 17, 17); color:  #c49b63; border: none;">Lowest Rating</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Reviews List -->
                    <div id="reviews-container">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $first_letter = strtoupper(substr($row['name'], 0, 1));
                                $rating = $row['rating'];
                                $date = date('M d, Y', strtotime($row['review_date']));
                                
                                echo '<div class="review-card" data-rating="' . $rating . '">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar" font-size:13px>' . $first_letter . '</div>
                                            <div>
                                                <h4>' . htmlspecialchars($row['name']) . '</h4>
                                                <div>';
                                
                                // Display rating stars
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<span class="star-display">&#9733;</span>';
                                    } else {
                                        echo '<span class="empty-star">&#9733;</span>';
                                    }
                                }
                                
                                echo '</div>
                                            </div>
                                        </div>
                                        <div class="review-date">' . $date . '</div>
                                    </div>
                                    <div class="review-content">
                                        <p>' . htmlspecialchars($row['comment']) . '</p>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<div class="no-reviews">
                                <h3>No reviews yet</h3>
                                <p>Be the first to leave a review</p>
                                <a href="review.php" class="write-review-btn mt-4">Write a Review</a>
                            </div>';
                        }
                        ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($result->num_rows > 5): ?>
                    <div class="pagination">
                        <a href="#" class="active">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#">&raquo;</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="ftco-footer ftco-section img">
        <div class="overlay"></div>
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">About Us</h2>
                        <p>BeautyBlend offers premium beauty services in a tranquil environment. Experience our personalized treatments designed for your unique needs.</p>
                        <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                            <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Recent Reviews</h2>
                        <?php
                        // Reset the result pointer to beginning
                        mysqli_data_seek($result, 0);
                        $count = 0;
                        while ($row = $result->fetch_assoc()) {
                            if ($count < 2) {
                                echo '<div class="block-21 mb-4 d-flex">
                                    <div class="text">
                                        <h3 class="heading"><a href="#">' . substr(htmlspecialchars($row['comment']), 0, 70) . '...</a></h3>
                                        <div class="meta">
                                            <div><span class="icon-calendar"></span> ' . date('M d, Y', strtotime($row['review_date'])) . '</div>
                                            <div><span class="icon-person"></span> ' . htmlspecialchars($row['name']) . '</div>
                                        </div>
                                    </div>
                                </div>';
                                $count++;
                            } else {
                                break;
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4 ml-md-4">
                        <h2 class="ftco-heading-2">Services</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">Hair Styling</a></li>
                            <li><a href="#" class="py-2 d-block">Makeup</a></li>
                            <li><a href="#" class="py-2 d-block">Facial Treatment</a></li>
                            <li><a href="#" class="py-2 d-block">Nail Care</a></li>
                            <li><a href="#" class="py-2 d-block">Body Massage</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Have a Question?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><span class="icon icon-map-marker"></span><span class="text">123 Beauty Street, City Center, State</span></li>
                                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+1 234 567 8900</span></a></li>
                                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@beautyblend.com</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | BeautyBlend
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
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
    
    <!-- Filter and Sort Reviews Script -->
    <script>
    $(document).ready(function() {
        // Filter reviews by star rating
        $('.filter-btn').click(function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            const rating = $(this).data('rating');
            if (rating === 'all') {
                $('.review-card').show();
            } else {
                $('.review-card').hide();
                $(`.review-card[data-rating="${rating}"]`).show();
            }
        });
        
        // Sort reviews
        $('#sort-select').change(function() {
            const sortBy = $(this).val();
            const container = $('#reviews-container');
            const reviews = $('.review-card').get();
            
            reviews.sort(function(a, b) {
                if (sortBy === 'newest') {
                    return $(b).find('.review-date').text().localeCompare($(a).find('.review-date').text());
                } else if (sortBy === 'oldest') {
                    return $(a).find('.review-date').text().localeCompare($(b).find('.review-date').text());
                } else if (sortBy === 'highest') {
                    return parseInt($(b).data('rating')) - parseInt($(a).data('rating'));
                } else if (sortBy === 'lowest') {
                    return parseInt($(a).data('rating')) - parseInt($(b).data('rating'));
                }
            });
            
            $.each(reviews, function(index, review) {
                container.append(review);
            });
        });
    });
    </script>
</body>
</html>