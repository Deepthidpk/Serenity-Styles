<?php
include 'connect.php'; // Include your database connection file

if (!isset($_SESSION['username'])) { // Checks if the user is logged in
    header('Location: login.php'); // Redirects to login.php if the user is not logged in
    exit(); // It's good practice to call exit() after header to stop further script execution
}

$sql = "SELECT 
            chk.checkout_id,
            chk.user_id,
            chk.address,
            chk.state,
            chk.district,
            chk.pincode,
            chk.phone_no AS checkout_phone_no,
            chk.city,
            chk.status AS checkout_status,
            chkp.chk_id AS checkout_product_id,
            chkp.product_id,
            chkp.quantity,
            p.product_name,
            p.product_image
        FROM 
            tbl_checkout chk
        JOIN 
            tbl_checkout_products chkp ON chk.checkout_id = chkp.checkout_id
        JOIN 
            tbl_products p ON chkp.product_id = p.product_id
        ORDER BY 
            chk.checkout_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Orders</title>
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
        .profile-img img {
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
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
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
        <li><?php echo "Hi, " . $_SESSION['username']; ?></li>
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
            <h2>All Orders</h2>
            <input type="text" class="search-bar" placeholder="Search...">
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        
                        <th>Name</th>
                        <!-- <th>Address</th>
                        <th>State</th>
                        <th>District</th>
                        <th>City</th>
                        <th>Pincode</th> -->
                        <th>Phone No</th>
                        <th>Product Image</th>
                        
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <?php
                        $sql1="SELECT name FROM tbl_user WHERE user_id=$row[user_id]";
                        $result1=$conn->query($sql1);
                        if($result1->num_rows>0){
                            $row1=$result1->fetch_assoc();
                        }
                        ?>
<tr>
    <td><?php echo $row['checkout_id']; ?></td>
    <td><?php echo $row1['name']; ?></td>
    <td><?php echo $row['checkout_phone_no']; ?></td>
    <td class="image-prod">
        <img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" width="80" height="80">
    </td>
    
    <td><?php echo $row['product_name']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td><?php echo $row['checkout_status']; ?></td>
</tr>

                    <?php } ?>
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
<?php
mysqli_close($conn);
?>
