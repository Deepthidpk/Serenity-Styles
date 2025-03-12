<?php
include("connect.php");

require __DIR__ . "/vendor/autoload.php";

// Create a Google Client object

$client=new Google\Client;
$client->setClientId("91981920181-u001cgasvcrtcpblsfev8mhuccle262f.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/coffeeduplicate/userindex.php");
$client->addScope('email');
$client->addScope('profile');

if(!$_SESSION["access_token"]){


// Exchange the authorization code for an access token
if (isset($_GET['code'])) {
	
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;

    // Create a Google OAuth2 service
    $google_oauth = new Google_Service_Oauth2($client);

    // Get user info
    $user_info = $google_oauth->userinfo->get();

    // Get user details
    $email = $user_info->email;
    $name = $user_info->name;

    // Check if the user already exists
    $stmt = $conn->prepare('SELECT user_id FROM tbl_login WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Insert the new user into tbl_login
        $stmt->close();
		$stmt = $conn->prepare('INSERT INTO tbl_user ( `name`) VALUES (?)');
            $stmt->bind_param('s', $name);
        
        if ($stmt->execute()) {
            // Get the last inserted user_id
            $user_id = $conn->insert_id;

            // Insert into tbl_user
            $stmt->close();
            $stmt = $conn->prepare('INSERT INTO tbl_login (user_id,email) VALUES (?,?)');
        $stmt->bind_param('is', $user_id,$email);
            $stmt->execute();
        }
    } else {
        $stmt->bind_result($user_id);
        $stmt->fetch();
    }
    $stmt->close();

    // Set session variables
    $_SESSION["email"] = $email;

    // Fetch user role
    $stmt = $conn->prepare("SELECT user_id, role FROM tbl_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["username"] = $row['role'];
        $_SESSION["user_id"] = $row["user_id"];
    } else {
        $_SESSION["username"] = null;
    }

    $stmt->close();
}
}
// If the user is not logged in, destroy the session and redirect
if (!isset($_SESSION['username']) || $_SESSION['username'] != "user") {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header('Location: login.php');
    exit(); // Stop further execution
}

$email = $_SESSION["email"];

// Fetch user name
$stmt = $conn->prepare("SELECT u.name AS name FROM tbl_user u 
                        JOIN tbl_login l ON u.user_id = l.user_id 
                        WHERE l.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>BeautyBlend</title>
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

    <style>
    .profile-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .profile-dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        z-index: 1000;
        border-radius: 4px;
        margin-top: 10px;
    }
    
    .profile-dropdown:hover .profile-dropdown-content {
        display: block;
    }
    
    .profile-dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s;
    }
    
    .profile-dropdown-content a:hover {
        background-color: #f8f9fa;
    }

    .profile-username {
        color: #c49b63;
        margin-left: 5px;
    }

    .profile-dropdown-content .divider {
        height: 1px;
        background-color: #e9ecef;
        margin: 0;
    }

    .dropdown-menu-right {
        right: 0;
        left: auto;
    }
    .icon-user-circle-o {
        color: #c49b63;
    }
    .dropdown-item i {
        width: 20px;
        color: #c49b63;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    </style>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="userindex.php">Beauty<small>Blend</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="userindex.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="shop.php" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Products</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="shop.php">Products</a>
                        <a class="dropdown-item" href="product-single.php">Single Product</a>
                        <a class="dropdown-item" href="cart.php">Cart</a>
                        <a class="dropdown-item" href="checkout.php">Checkout</a>
                    </div>
                </li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
               
                
                <?php if(isset($_SESSION['username'])){?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="images/profile2.jpg" alt="Profile" id="profile-icon" class="rounded-circle" style="width: 30px; height: 30px;">
        </a>
        <?php
		$user_id=$_SESSION['user_id'];
		?>
<div class="dropdown-menu" aria-labelledby="profileDropdown">
<a class="dropdown-item" href="profile.php?user_id=<?php echo $user_id; ?>"><?php echo $row['name']; ?></a>
    <a class="dropdown-item" href="profile.php?user_id=<?php echo $user_id; ?>">Profile</a>
   
    <a class="dropdown-item" href="viewappointments.html?user_id=<?php echo $user_id; ?>">View Appointments</a>
    <a class="dropdown-item" href="vieworders.html?user_id=<?php echo $user_id; ?>">View Orders</a>
    <a class="dropdown-item" href="logout.php?user_id=<?php echo $user_id; ?>">Log Out</a>
</div>

    </li> <?php } ?>



                <li class="nav-item cart">
                    <a href="cart.php" class="nav-link">
                        <span class="icon icon-shopping_cart"></span>
                        <span class="bag d-flex justify-content-center align-items-center">
                            <small>1</small>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <section class="home-slider owl-carousel">
      <div class="slider-item" style="background-image: url(images/coverpage.jpg);">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-md-8 col-sm-12 text-center ftco-animate">
            	<span class="subheading">Welcome</span>
              <h1 class="mb-4">Serenity Styles </h1>
              <p class="mb-4 mb-md-5">"Experience luxury and transformation at our beauty salon, where expert care unveils your radiant glow."</p>
              
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url(images/coverpage1.jpg);">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-md-8 col-sm-12 text-center ftco-animate">
            	<span class="subheading">Welcome</span>
              <h1 class="mb-4">Glow with confidence, &amp; shine with beauty</h1>
              <p class="mb-4 mb-md-5">"Experience luxury and transformation at our beauty salon, where expert care unveils your radiant glow."</p>
              
            </div>

          </div>
        </div>
      </div>

      <div class="slider-item" style="background-image: url(images/coverpage2.jpg);">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

            <div class="col-md-8 col-sm-12 text-center ftco-animate">
            	<span class="subheading">Welcome</span>
              <h1 class="mb-4">Enhance your beauty, and let your radiance shine through!</h1>
              <p class="mb-4 mb-md-5">"Experience luxury and transformation at our beauty salon, where expert care unveils your radiant glow."</p>
              
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
	    						<p>"Experience luxury and transformation at our beauty salon, where expert care unveils your radiant glow."</p>
	    					</div>
	    				</div>
	    				<div class="col-md-4 d-flex ftco-animate">
	    					<div class="icon"><span class="icon-my_location"></span></div>
	    					<div class="text">
	    						<h3>Hill top Street</h3>
	    						<p>	Nilambur,<br>Malappuram,<br>Kerala,<br>India</p>
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
	    		
    		</div>
    	</div>
    </section>

    <section class="ftco-about d-md-flex">
    	<div class="one-half img" style="background-image: url(images/ourstory.jpg);"></div>
    	<div class="one-half ftco-animate">
    		<div class="overlap">
	        <div class="heading-section ftco-animate ">
	        	<span class="subheading">Discover</span>
	          <h2 class="mb-4">Our Story</h2>
	        </div>
	        <div>
	  				<p>At our beauty salon, we believe in more than just looking good—we believe in feeling amazing. From the moment you walk through our doors, you're greeted with warmth and care. Our team of experts tailors each treatment to highlight your unique beauty, leaving you refreshed, confident, and glowing. Step in for a transformation, and leave with a renewed sense of self. Your beauty journey starts here.</p>
	  			</div>
  			</div>
    	</div>
    </section>

    

    <section class="ftco-section">
    	<div class="container">
    		<div class="row align-items-center">
    			<div class="col-md-6 pr-md-5">
    				<div class="heading-section text-md-right ftco-animate">
	          	<span class="subheading">Explore</span>
	            <h2 class="mb-4">Our Products</h2>
	            <p class="mb-4">Our beauty products are crafted with the finest ingredients to enhance your natural glow. From skincare to haircare, each product is designed to nourish, protect, and transform, helping you look and feel your best every day.</p>
	            <p><a href="shop.html" class="btn btn-primary btn-outline-primary px-4 py-3">View Full Products</a></p>
	          </div>
    			</div>
    			<div class="col-md-6">
    				<div class="row">
    					<div class="col-md-6">
    						<div class="menu-entry">
		    					<a href="#" class="img" style="background-image: url(images/niveafacewash2.jpg);"></a>
		    				</div>
    					</div>
    					<div class="col-md-6">
    						<div class="menu-entry mt-lg-4">
		    					<a href="#" class="img" style="background-image: url(images/olaycream3.jpg);"></a>
		    				</div>
    					</div>
    					<div class="col-md-6">
    						<div class="menu-entry">
		    					<a href="#" class="img" style="background-image: url(images/macfoundation.jpg);"></a>
		    				</div>
    					</div>
    					<div class="col-md-6">
    						<div class="menu-entry mt-lg-4">
		    					<a href="#" class="img" style="background-image: url(images/aveeno.jpg);"></a>
		    				</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url(images/makeupsection.jpg);" data-stellar-background-ratio="0.5">
			<div class="overlay"></div>
      <div class="container">
        <div class="row justify-content-center">
        	<div class="col-md-10">
        		<div class="row">
		          
		          </div>
        </div></div>
    </section>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
          	<span class="subheading">Explore</span>
            <h2 class="mb-4">Best Services</h2>
            <p>Our beauty salon offers a range of personalized services, including haircuts, styling, facials, manicures, and more, all designed to enhance your natural beauty. Each treatment is tailored to meet your unique needs, ensuring a relaxing and rejuvenating experience.</p>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/haircut.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Haircut</a></h3>
    						<p>Our skilled stylists craft the perfect cut for a fresh, flattering look that complements your style.</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="services.html" class="btn btn-primary btn-outline-primary">View Haircut</a></p>
    					</div>
    				</div>
        	</div>
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/facial.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Facial</a></h3>
    						<p>Our rejuvenating facials cleanse, hydrate, and refresh your skin for a radiant, glowing complexion.</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="services.html" class="btn btn-primary btn-outline-primary">View Facial</a></p>
    					</div>
    				</div>
        	</div>
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/manicure.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Manicure</a></h3>
    						<p>A manicure shapes nails, cares for cuticles, and adds polish for a neat look.</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="services.html" class="btn btn-primary btn-outline-primary">View Manicure</a></p>
    					</div>
    				</div>
        	</div>
        	<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/makeup2.jpg);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#">Makeup</a></h3>
    						<p>Our professional makeup services enhance your features, creating a flawless look for any occasion.</p>
    						<p class="price"><span>$5.90</span></p>
    						<p><a href="services.html" class="btn btn-primary btn-outline-primary">View Makeup</a></p>
    					</div>
    				</div>
        	</div>
        </div>
    	</div>
    </section>

    
		<section class="ftco-menu">
    	<div class="container">
    		<div class="row justify-content-center mb-5">
          <div class="col-md-7 heading-section text-center ftco-animate">
          	<span class="subheading">Explore</span>
            <h2 class="mb-4">Our Products</h2>
            <p>Our beauty products are expertly crafted to nourish and enhance your skin, hair, and nails, delivering visible results with every use.</p>
          </div>
        </div>
    		<div class="row d-md-flex">
	    		<div class="col-lg-12 ftco-animate p-md-5">
		    		<div class="row">
		          <div class="col-md-12 nav-link-wrap mb-5">
		            <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
		              <a class="nav-link active" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Facewash</a>

		              <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab" aria-controls="v-pills-2" aria-selected="false">Creams</a>

		              <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab" aria-controls="v-pills-3" aria-selected="false">Foundation</a>
		            </div>
		          </div>
		          <div class="col-md-12 d-flex align-items-center">
		            
		            <div class="tab-content ftco-animate" id="v-pills-tabContent">

		              <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
		              	<div class="row">
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/niveafacewash2.jpg);"></a>
		              				<div class="text">
		              					<h3><a href="#">Nivea</a></h3>
		              					<p>Nivea Facewash deeply cleanses and refreshes the skin, leaving it smooth, soft, and nourished.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/cataphil2.png);"></a>
		              				<div class="text">
		              					<h3><a href="#">Cetaphil</a></h3>
		              					<p>Cetaphil Facewash gently cleanses and hydrates, leaving skin soft and refreshed without stripping moisture.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/neutrogena.png);"></a>
		              				<div class="text">
		              					<h3><a href="#">Neutrogena </a></h3>
		              					<p>Neutrogena Facewash deeply cleanses, removing dirt and oil, leaving your skin fresh, clear, and soft.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              	</div>
		              </div>

		              <div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
		                <div class="row">
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/olaycream3.jpg);"></a>
		              				<div class="text">
		              					<h3><a href="#">Olay White</a></h3>
		              					<p>Olay White Cream brightens and evens skin tone, providing deep hydration for a radiant, youthful glow.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/pondswhitecream.webp);"></a>
		              				<div class="text">
		              					<h3><a href="#">Pond's White</a></h3>
		              					<p>Pond's White Cream helps lighten skin, reduce dark spots, and provides a smooth, radiant complexion.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/himalayaglow.jpg);"></a>
		              				<div class="text">
		              					<h3><a href="#">Himalaya Glow</a></h3>
		              					<p>Himalaya Glow evens skin tone, brightens complexion, and nourishes with natural ingredients for a healthy, radiant look.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              	</div>
		              </div>

		              <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
		                <div class="row">
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/maybellinefound2.jpg);"></a>
		              				<div class="text">
		              					<h3><a href="#">Maybelline Fit Me</a></h3>
		              					<p>Maybelline Fit Me Foundation offers natural coverage, matches your skin tone, and controls shine for a flawless finish.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/lorealfound1.jpg);"></a>
		              				<div class="text">
		              					<h3><a href="#"> L'Oréal Infallible</a></h3>
		              					<p>L'Oréal Infallible Foundation offers long-lasting, full coverage that stays fresh and matte all day, ensuring a flawless finish.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="col-md-4 text-center">
		              			<div class="menu-wrap">
		              				<a href="#" class="menu-img img mb-4" style="background-image: url(images/macfoundation.jpg);"></a>
		              				<div class="text">
		              					<h3><a href="#">MAC Studio Fix</a></h3>
		              					<p>MAC Studio Fix Foundation provides buildable coverage, a matte finish, and all-day wear for a smooth, flawless complexion.</p>
		              					<p class="price"><span>$2.90</span></p>
		              					<p><a href="cart.html" class="btn btn-primary btn-outline-primary">Add to cart</a></p>
		              				</div>
		              			</div>
		              		</div>
		              	</div>
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </div>
    	</div>
    </section>

    <section class="ftco-section img" id="ftco-testimony" style="background-image: url(images/customertestimony.jpg);"  data-stellar-background-ratio="0.5">
    	<div class="overlay"></div>
	    <div class="container">
	      <div class="row justify-content-center mb-5">
	        <div class="col-md-7 heading-section text-center ftco-animate">
	        	<span class="subheading">Testimony</span>
	          <h2 class="mb-4">Customers Says</h2>
	          <p>A customer might describe a beauty salon as welcoming with skilled staff, quality treatments, and a clean, comfortable environment, emphasizing great service and a relaxing experience.</p>
	        </div>
	      </div>
	    </div>
	    <div class="container-wrap">
	      <div class="row d-flex no-gutters">
	        <div class="col-lg align-self-sm-end ftco-animate">
	          <div class="testimony">
	             <blockquote>
	                <p>&ldquo;Serenity Styles Beauty Salon is a wonderful experience with friendly staff, excellent service, and a relaxing atmosphere. They'd highlight the quality of treatments and the salon's cleanliness, making it a top choice for pampering.&rdquo;</p>
	              </blockquote>
	              <div class="author d-flex mt-4">
	                <div class="image mr-3 align-self-center">
	                  <img src="images/person_1.jpg" alt="">
	                </div>
	                <div class="name align-self-center">Alinta Therese </div>
	              </div>
	          </div>
	        </div>
	        <div class="col-lg align-self-sm-end">
	          <div class="testimony overlay">
	             <blockquote>
	                <p>&ldquo;Serenity Styles Beauty Salon offers a fantastic experience with professional staff, great service, and a relaxing environment. The treatments are high quality, and the salon is always clean and inviting. Highly recommend!&rdquo;</p>
	              </blockquote>
	              <div class="author d-flex mt-4">
	                
	                <div class="name align-self-center">Lissa Ann Jhon </div>
	              </div>
	          </div>
	        </div>
	        <div class="col-lg align-self-sm-end ftco-animate">
	          <div class="testimony">
	             <blockquote>
	                <p>&ldquo;The beauty salon uses amazing products that leave my skin and hair feeling great. Highly effective and high-quality! &rdquo;</p>
	              </blockquote>
	              <div class="author d-flex mt-4">
	                
	                <div class="name align-self-center">Heliza Jinson </div>
	              </div>
	          </div>
	        </div>
	        <div class="col-lg align-self-sm-end">
	          <div class="testimony overlay">
	             <blockquote>
	                <p>&ldquo;Serenity Styles Beauty Salon provides exceptional services. The staff is friendly, professional, and always delivers great results!&rdquo;</p>
	              </blockquote>
	              <div class="author d-flex mt-4">
	                
	                <div class="name align-self-center">Karthika Kishore </div>
	              </div>
	          </div>
	        </div>
	        <div class="col-lg align-self-sm-end ftco-animate">
	          <div class="testimony">
	            <blockquote>
	              <p>&ldquo; Serenity Styles Beauty Salon has excellent customer interaction. The staff is attentive, friendly, and always makes you feel valued! &rdquo;</p>
	            </blockquote>
	            <div class="author d-flex mt-4">
	              
	              <div class="name align-self-center">Dhyuvika Deepak </div>
	            </div>
	          </div>
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
              <p>We offers a relaxing atmosphere with professional services, including haircuts, facials, and manicures. The salon is known for its friendly staff, quality treatments, and attention to detail.</p>
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
	                <li><span class="icon icon-map-marker"></span><span class="text"> Hill top Street<br>Nilambur,<br>Malappuram,<br>Kerala,<br>India</span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">8590918598</span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            

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
    
  </body>
</html>
</body>
</html>
