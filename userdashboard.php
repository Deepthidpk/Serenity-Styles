<!DOCTYPE html>
<html lang="en">
<head>
    <title>Enchanted PLumes</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="index.js"></script>
</head>

<style>
        main {
    padding: 20px;
}

/* Gallery styles */
.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.gallery-item {
    border: 1px solid #ccc;
    overflow: hidden;
}

.gallery-item img {
    width: 100%;
    height: auto;
    display: block;
}
</style>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <a href="index.php"><img src="logo-typo.png" alt=""></a>
            </div> 
            <div class="menu-list">
                <ul class="nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="user-service.php">Services</a></li>
                    <li><a href="user-gallery.php">Gallery</a></li>
                    <li><a href="user-about.php">About</a></li>
                    <li><a href="user-contact.php">Contact us</a></li>
                    <?php
require_once("connect.php");
session_start(); 
$userId = $_SESSION['username'];
if(!isset($userId)){
    header('location:login.php');
 }
                $userDetails = getUserDetails($userId ); 
?>
                <li class="span"><a href=""><?php echo "hi ".$userDetails['username']; ?></a></li>
                </ul>
<!-- ----------------------------------------------------------------------- -->

<?php  
                echo '<div class="profile-dropdown">';
                $image = $userDetails['user_pic'];
                echo '<img src="'.$image.'" alt="Profile" id="profile-icon" onclick="toggleDropdown()">';
                echo '<div class="dropdown-content" id="profile-dropdown-content">';
                echo '<a href="userprofileview.php">View Profile</a>';
                echo '<a href="userprofileupdate.php">Update Profile</a>';
                echo '<a href="logout.php">Log out</a>';
                echo '</div>';
                echo '</div>';
?>

<!-- ----------------------------------------------------------------------- -->


                <!-- <div class="profile-dropdown">
                <img src="profile-icon.png" alt="Profile" id="profile-icon" onclick="toggleDropdown()">
                <div class="dropdown-content" id="profile-dropdown-content">
                       
                        
                    </div>
                </div> -->
<!-- ----------------------------------------------------------------------- -->
                
                
            </div>
        </div> 
        <div class="flyer">
            <div class="flyer-video">
                <video autoplay loop muted>
                    <source src="bgvideo.mp4" type="video/mp4">
                </video>
            </div>   
        </div>
        <div class="section">
        <main>
        <section class="gallery">
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
            <div class="gallery-item">
                <img src="img1.png" alt="Image 1">
            </div>
            <div class="gallery-item">
                <img src="img2.png" alt="Image 2">
            </div>
        </section>
    </main>
        </div>
    </div>
            


</body>
<?php
 
function getUserDetails($userId ) {
  
    require('connect.php');
    $sql = "SELECT * FROM tbl_login WHERE username = '$userId'"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
     
        $userDetails = $result->fetch_assoc();
    }

    $conn->close();

    return $userDetails;
}



?>

</html>