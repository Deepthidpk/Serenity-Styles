<?php
include 'connect.php';


if (!isset($_SESSION['username'])) { // Checks if the user is logged in

    header('Location: login.php'); // Redirects to login.php if the user is not logged in
    exit(); // It's good practice to call exit() after header to stop further script execution
}
//above is for security check ,prevent unauthorized access

// Fetch user data by joining tbl_login and tbl_user
$sql = "SELECT 
            l.login_id,
            l.user_id, 
            l.email, 
            l.role,
            l.status, 
            u.name, 
            u.phone_no 
        FROM 
            tbl_login l
        JOIN 
            tbl_user u 
        ON 
            l.user_id = u.user_id
        WHERE 
           
            l.role <>'admin'"; // Fetch only active users based on role or other criteria
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        /* Form Styles */
        .form-container {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #3d3d3d;
            background-color: #1a1a1a;
            color: #fff;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .chart-container {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            height: 300px;
        }
    </style>
</head>

<body>
    <!-- <div class="container"> -->
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
                <h2>Manage Users</h2>
                <input type="text" class="search-bar" placeholder="Search...">
            
            </div>

            <div class="cards-container">


            </div>









            <div class="table-container">
                <table id="dataTable">
                    <thead>
                        <tr>
                            <th>Sl.NO</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Phone No</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
if ($result->num_rows > 0) {
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $i . "</td>
            <td>" . htmlspecialchars($row["email"]) . "</td>
            <td>" . htmlspecialchars($row["role"]) . "</td>
            <td>" . htmlspecialchars($row["name"]) . "</td>
            <td>" . htmlspecialchars($row["phone_no"]) . "</td>
            <td>" . htmlspecialchars($row["status"]) . "</td>
            <td>";
        
        // Check user status to determine which button to show
        if ($row['status'] == 'Active') {
            echo "<a href='update_status.php?id=" . $row['user_id'] . "&status=Inactive' class='btn btn-xs btn-danger'>Active</a>";
        } else {
            echo "<a href='update_status.php?id=" . $row['user_id'] . "&status=Active' class='btn btn-xs btn-success'>Inactive</a>";
        }
        
        echo "</td>
          </tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='6'>No active users found.</td></tr>";
}
?>

                    </tbody>
                </table>
            </div>




        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchBar = document.querySelector(".search-bar");
        const tableRows = document.querySelectorAll("tbody tr");

        searchBar.addEventListener("keyup", function () {
            const searchText = searchBar.value.toLowerCase();

            tableRows.forEach(row => {
                const productName = row.cells[1].textContent.toLowerCase();
                if (productName.includes(searchText)) {
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