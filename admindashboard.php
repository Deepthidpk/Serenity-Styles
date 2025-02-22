<?php


// Database Connection
include 'connect.php'; // Ensure you have a db_connect.php file that sets up the $conn variable
if(!isset($_SESSION['username']) || $_SESSION['username']!="admin"){ // Checks if the user is logged in
    
   // Unset all session variables
   $_SESSION = array();

   // Destroy the session
   session_destroy();
	header('Location: login.php'); // Redirects to login.php if the user is not logged in
    exit(); // It's good practice to call exit() after header to stop further script execution
}
// Fetch total users
$userQuery = "SELECT COUNT(*) AS total_users FROM tbl_login WHERE status='active'";
$userResult = mysqli_query($conn, $userQuery);
$userRow = mysqli_fetch_assoc($userResult);
$totalUsers = $userRow['total_users'];

// Fetch total products
$productQuery = "SELECT COUNT(*) AS total_products FROM tbl_products WHERE status='available'";
$productResult = mysqli_query($conn, $productQuery);
$productRow = mysqli_fetch_assoc($productResult);
$totalProducts = $productRow['total_products'];

// Fetch total services
$serviceQuery = "SELECT COUNT(*) AS total_services FROM tbl_services WHERE status='active'";
$serviceResult = mysqli_query($conn, $serviceQuery);
$serviceRow = mysqli_fetch_assoc($serviceResult);
$totalServices = $serviceRow['total_services'];

// Fetch total appointments
// $appointmentQuery = "SELECT COUNT(*) AS total_appointments FROM tbl_appointments";
// $appointmentResult = mysqli_query($conn, $appointmentQuery);
// $appointmentRow = mysqli_fetch_assoc($appointmentResult);
// $totalAppointments = $appointmentRow['total_appointments'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            width: 100%;
           height: 100vh;
            background-image: url(images/dash5.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            color: #fff;
        }

        .container {
            display: flex;
            
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
           
            height: 100vh;
            padding: 20px;
            position: fixed;
            background-color: rgba(45, 45, 45, 0.9);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-img img{
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            background-color: #4a4a4a;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            margin-bottom: 15px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #3d3d3d;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-bar {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #2d2d2d;
            color: #fff;
            width: 300px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: rgba(45, 45, 45, 0.9); /* 0.9 is the alpha value */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card h3 {
            margin-bottom: 10px;
        }

        /* Chart Styles */
        .chart-container {
            background-color: rgba(45, 45, 45, 0.9);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            height: 300px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="profile-img">
                <img src="images/adminprofile.jpg">
                </div>
                <h3>Admin Panel</h3>
            </div>
            <ul class="nav-links">
                <li><?php echo "Hi, " . $_SESSION['username']; ?></li>
                <li><a href="admindashboard.php">Dashboard</a></li>
                <li><a href="viewservices.php">Services</a></li>
                <li><a href="viewproducts.php">Products</a></li>
                <li><a href="viewappointments.php">Appointments</a></li>
                <li><a href="viewuser.php">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Dashboard Overview</h2>
               
            </div>

            <div class="cards-container">
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsers; ?></p>
                </div>
                <div class="card">
                    <h3>Total Products</h3>
                    <p><?php echo $totalProducts; ?></p>
                </div>
                <div class="card">
                    <h3>Total Services</h3>
                    <p><?php echo $totalServices; ?></p>
                </div>
                <div class="card">
                    <h3>Total Appointments</h3>
                    <!-- <p><?php echo $totalAppointments; ?></p> -->
                </div>
            </div>

            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div> 
        </div>
    </div>

<script>
        // Initialize Chart
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Monthly Activity',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#4CAF50',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
