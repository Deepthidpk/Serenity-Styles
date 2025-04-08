<?php
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM tbl_appointment WHERE status='Pending' OR status='Approved'";
if (isset($_POST['filter_month']) && !empty($_POST['filter_month'])) {
    $selected_month = $_POST['filter_month'];
    $year = substr($selected_month, 0, 4);
    $month = substr($selected_month, 5, 2);
    $sql = "SELECT * FROM tbl_appointment WHERE (status='Pending' OR status='Approved') AND YEAR(date) = '$year' AND MONTH(date) = '$month'";
}
$result = $conn->query($sql);

if (isset($_GET['msg'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['msg']) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .month-picker {
            padding: 5px;
            border-radius: 5px;
            border: none;
            background-color: #2d2d2d;
            color: #fff;
        }
        
        .filter-btn {
            padding: 5px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .filter-btn:hover {
            background-color: #45a049;
        }
        
        .reset-btn {
            padding: 5px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .reset-btn:hover {
            background-color: #d32f2f;
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
            <li><a href="vieworders.php">Orders</a></li>
            <li><a href="viewreview.php">Reviews</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Manage Users</h2>
            <input type="text" class="search-bar" placeholder="Search...">
        </div>

        <form method="post" action="" class="filter-container">
            <label for="filter_month">Filter by Month:</label>
            <input type="month" id="filter_month" name="filter_month" class="month-picker" value="<?php echo isset($_POST['filter_month']) ? $_POST['filter_month'] : ''; ?>">
            <button type="submit" class="filter-btn">Apply Filter</button>
            <button type="submit" class="reset-btn" name="reset">Reset</button>
        </form>

        <div class="cards-container"></div>

        <div class="table-container">
            <table id="dataTable">
                <thead>
                    <tr>
                        <th>Sl.NO</th>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            $sql = "SELECT service_name FROM tbl_services WHERE service_id=$row[service_id] AND status='active'";
                            $result1 = $conn->query($sql);
                            if ($result1->num_rows > 0) {
                                $col = $result1->fetch_assoc();
                            }

                            echo "<tr>
                            <td>" . $i . "</td>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>" . htmlspecialchars($row["phone_no"]) . "</td>
                            <td>" . htmlspecialchars($row["date"]) . "</td>
                            <td>" . htmlspecialchars($row["time"]) . "</td>
                            <td>" . htmlspecialchars($col["service_name"]) . "</td>
                            <td>" . htmlspecialchars($row["status"]) . "</td>
                            <td>";

                            if ($row['status'] == 'Pending') {
                                echo "<button class='btn btn-xs btn-danger swal-action' data-id='" . $row['appointment_id'] . "' data-status='Rejected'>Reject</button> ";
                                echo "<button class='btn btn-xs btn-success swal-action' data-id='" . $row['appointment_id'] . "' data-status='Approved'>Accept</button>";
                            } elseif ($row['status'] == 'Approved') {
                                echo "<button class='btn btn-xs btn-success' disabled>Approved</button> ";
                                echo "<button class='btn btn-xs btn-danger swal-action' data-id='" . $row['appointment_id'] . "' data-status='Cancelled'>Cancel</button>";
                            }

                            echo "</td></tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='8'>No appointments found.</td></tr>";
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
                    row.style.display = userName.includes(searchText) ? "" : "none";
                });
            });

            const swalButtons = document.querySelectorAll(".swal-action");
            swalButtons.forEach(btn => {
                btn.addEventListener("click", function () {
                    const id = this.dataset.id;
                    const status = this.dataset.status;

                    Swal.fire({
                        title: `Are you sure?`,
                        text: `You are about to mark this appointment as "${status}".`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, ${status} it!`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `update_appointmentstatus.php?id=${id}&status=${status}`;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>

    