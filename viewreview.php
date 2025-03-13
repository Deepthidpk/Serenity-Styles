<?php
include 'connect.php';

if (!isset($_SESSION['username'])) { // Checks if the user is logged in
    header('Location: login.php'); // Redirects to login.php if the user is not logged in
    exit(); // It's good practice to call exit() after header to stop further script execution
}
//above is for security check, prevent unauthorized access

// Fetch reviews data by joining tbl_reviews with tbl_user
$sql = "SELECT 
            r.review_id,
            r.user_id, 
            r.comment, 
            r.rating,
            r.review_date, 
            
            u.name
        FROM 
            tbl_review r
        JOIN 
            tbl_user u 
        ON 
            r.user_id = u.user_id
        ORDER BY
            r.review_date DESC"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reviews</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
            background-attachment: fixed;
            color: #fff;
        }

        .container {
            display: flex;
            margin: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: rgba(45, 45, 45, 0.9);
            height: 100vh;
            padding: 20px;
            position: fixed;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .sidebar-header h3{
            font-size: 20px;
            
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
            padding: 3px;
            border-radius: 5px;
            border: none;
            background-color: #2d2d2d;
            color: #fff;
            width: 220px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card h3 {
            margin-bottom: 10px;
        }

        /* Table Styles */
        .table-container {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            background-color: rgba(45, 45, 45, 0.9);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }

        /* Rating Stars */
        .rating-stars {
            color: gold;
        }

        /* Status Badge */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .status-approved {
            background-color: #28a745;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-rejected {
            background-color: #dc3545;
        }

        /* Button Styles */
        .btn-action {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="profile-img">
                <img src="images/adminprofile.jpg">
            </div>
            <h3>Admin Panel</h3>
        </div>
        <ul class="nav-links">
            <li><?php echo "Hii, " . $_SESSION['username']; ?></li>
            <li><a href="admindashboard.php">Dashboard</a></li>
            <li><a href="viewservices.php">Services</a></li>
            <li><a href="viewproducts.php">Products</a></li>
            <li><a href="manage_appointment.php">Appointments</a></li>
            <li><a href="viewuser.php">Users</a></li>
            <li><a href="viewreview.php">Reviews</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>View Reviews</h2>
            <input type="text" class="search-bar" placeholder="Search...">
        </div>

        <div class="cards-container">
            <div class="card">
                <h3>Total Reviews</h3>
                <p><?php echo $result->num_rows; ?></p>
            </div>
            <div class="card">
                <h3>Average Rating</h3>
                <p>
                <?php
                    // Calculate average rating
                    $avg_query = "SELECT AVG(rating) as avg_rating FROM tbl_review";
                    $avg_result = $conn->query($avg_query);
                    $avg_row = $avg_result->fetch_assoc();
                    echo number_format($avg_row['avg_rating'], 1) . " / 5.0";
                ?>
                </p>
            </div>
            <div class="card">
                <h3>Recent Reviews</h3>
                <p>
                <?php
                    // Count recent reviews (last 7 days)
                    $recent_query = "SELECT COUNT(*) as recent_count FROM tbl_review WHERE review_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                    $recent_result = $conn->query($recent_query);
                    $recent_row = $recent_result->fetch_assoc();
                    echo $recent_row['recent_count'] . " (last 7 days)";
                ?>
                </p>
            </div>
        </div>

        <div class="table-container">
            <table id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $j=1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $j . "</td>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>
                                <div class='rating-stars'>";
                                // Display stars based on rating
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $row["rating"]) {
                                        echo "<i class='fas fa-star'></i>";
                                    } else {
                                        echo "<i class='far fa-star'></i>";
                                    }
                                }
                        echo "</div>
                            </td>
                            <td>" . htmlspecialchars($row["comment"]) . "</td>
                            <td>" . date('M d, Y', strtotime($row["review_date"])) . "</td>
                            <td>";
                            
                        
                $j++;
                }
            }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchBar = document.querySelector(".search-bar");
        const tableRows = document.querySelectorAll("tbody tr");

        searchBar.addEventListener("keyup", function () {
            const searchText = searchBar.value.toLowerCase();

            tableRows.forEach(row => {
                const userName = row.cells[1].textContent.toLowerCase();
                const reviewText = row.cells[3].textContent.toLowerCase();
                
                if (userName.includes(searchText) || reviewText.includes(searchText)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
    </script>
</body>

</html>