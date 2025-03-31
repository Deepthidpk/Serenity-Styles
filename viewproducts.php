<?php
include 'connect.php';

// Fetch and display existing products
$sql = "SELECT * FROM tbl_products WHERE status='available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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

        .sidebar {
            width: 250px;
            background-color: rgba(45, 45, 45, 0.9);
            height: 100vh;
            padding: 20px;
            position: fixed;
        }
        .search-bar {
    padding: 3px;
    border-radius: 5px;
    border: none;
    background-color: #2d2d2d;
    color: #fff;
    width: 220px;
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
        .sidebar-header h3{
            font-size: 20px;
            
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li {
            margin-bottom: 15px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #3d3d3d;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .table-container,
        .form-container {
            
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            background-color: rgba(45, 45, 45, 0.9); 
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }

        th {
            background-color: #4a4a4a;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #3d3d3d;
            background-color: #1a1a1a;
            color: #fff;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #45a049;
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
            <p><?php echo "Hi, " . $_SESSION['username']; ?></p>
        <ul class="nav-links">
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
    <div class="d-flex justify-content-between align-items-center">
  <h2>Manage Products</h2>
  <input type="text" class="search-bar" placeholder="Search...">
  <a href="addproducts.php"><button type="button" class="btn btn-primary">Add Product</button></a>
  
</div>


        

        <!-- Product Table -->
        <div class="table-container">
            <h4>Product List</h4>
            <table>
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i=1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
        <td>" . $i . "</td>
        <td class='image-prod'>
            <img src='" . htmlspecialchars($row['product_image']) . "' alt='Product Image' width='80' height='80'>
        </td>
        <td>" . htmlspecialchars($row["product_name"]) . "</td>
        <td>" . htmlspecialchars($row["pro_description"]) . "</td>
        <td>" . htmlspecialchars($row["quantity"]) . "</td>
        <td>₹" . htmlspecialchars($row["price"]) . "</td>
        <td>
            <a href='editproduct.php?id=" . $row['product_id'] . "' class='btn btn-sm btn-primary'>Edit</a>
            <a href='deleteproduct.php?id=" . $row['product_id'] . "' class='btn btn-sm btn-danger'>Delete</a>
        </td>
    </tr>";

                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='6'>No products found.</td></tr>";
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
