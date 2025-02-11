<?php
include 'connect.php';


// Security check to prevent unauthorized access
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}


 // Fetch categories from the database
 $sql = "SELECT category_id, category_name FROM tbl_category WHERE status='available'";
 $result = $conn->query($sql);

 
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $pro_description = $_POST["pro_description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];
    // Handle file upload
    $target_dir = "images/product_images/"; //location of the image where it stored
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file); //move uploaded file to the directory
   

    $sql = "INSERT INTO tbl_products (product_name, pro_description, price,quantity,category_id,product_image)
            VALUES ('$product_name', '$pro_description', '$price','$quantity','$category','$target_file')";

if ($conn->query($sql) === TRUE) {
    echo "<div class='alert alert-success'>New product added successfully, redirecting to product list</div>";
    // Wait for 3 seconds and redirect
    echo "<script>
            setTimeout(function() {
                window.location.href = 'viewproducts.php';
            }, 3000);
          </script>";
} else {
    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

}


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
            background-color: #1a1a1a;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 250px;
            background-color: #2d2d2d;
            height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .sidebar-header {
            margin-bottom: 30px;
            color: #fff;
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
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
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
        .form-container textarea,
        .form-container select {
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
            <h3>Admin Panel</h3>
            <p><?php echo "Hi, " . $_SESSION['username']; ?></p>
        </div>
        <ul class="nav-links">
            <li><a href="admindashboard.php">Dashboard</a></li>
            <li><a href="viewservices.php">Services</a></li>
            <li><a href="viewproducts.php">Products</a></li>
            <li><a href="viewappointments.php">Appointments</a></li>
            <li><a href="viewuser.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Products</h2>

        <!-- Add New Product Form -->
        <div class="form-container">
    <h5>Add New Product</h5>
    <form method="post" id="product_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <input type="text" name="product_name" placeholder="Product Name" required>
    
    <!-- Dropdown for categories -->
    <select name="category" required>
        <option value="" disabled selected>Select Category</option>
        <?php
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['category_id']) . '">' . htmlspecialchars($row['category_name']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>No categories available</option>';
        }
        ?>
    </select>
    
    <textarea name="pro_description" placeholder="Description" rows="4" required></textarea>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="number" name="price" placeholder="Price" step="0.01" required>
    <div class="preview">
         <!-- Image preview -->
    <img id="image_preview" src="#" alt="Image Preview" style="display: none; max-width: 200px; margin-top: 10px;">
    
    </div>
    <!-- File input for product image -->
     
    <input type="file" name="product_image" accept="image/*" required onchange="previewImage(event)">
    
   
    <div>
        <button type="submit">Add Product</button>
    </div>
</form>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image_preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</div>


       
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
    // Custom validation methods
    jQuery.validator.addMethod('lettersonly', function (value, element) {
        return /^[^-\s][a-zA-Z_\s-]+$/.test(value);
    }, "Please use letters only.");

    jQuery.validator.addMethod('valid_Quantity', function (value, element) {
    return /^[+]?\d+$/.test(value) && parseInt(value, 10) > 0;
}, "Quantity should be a positive whole number greater than 0");

    jQuery.validator.addMethod('valid_Productprice', function (value, element) {
    return /^[+]?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value) && parseFloat(value) >= 100;
}, "Price should be minimum 100");

jQuery.validator.addMethod('productImage', function(value, element, param) {
        var extension = value.substring(value.lastIndexOf('.') + 1).toLowerCase();
        return extension === 'png' || extension === 'jpg' || extension === 'jpeg' || extension === 'svg';
    }),
    
    // Form validation rules and messages
    $('#product_form').validate({
        rules: {
            product_name: {
                required: true,
                lettersonly: true,
                minlength: 3
            },
            category: {
                required: true
                
            },
            pro_description: {
                required: true
                
                
            },
            quantity: {
            required: true,
            valid_Quantity: true
        },
            price: {
                required: true,
                valid_Productprice:true
                
            },
            product_image:{
                required:true,
                productImage:true
                
            }
            
        },
        messages: {
            product_name: {
                required: "Please enter your full name",
                lettersonly: "Name must be in alphabets only"
            },
            category: {
                required: "Please select a category"
            },   
            pro_description: {
                required: "Service description is required"
                
            },
            quantity: {
            required: "Please enter a quantity",
            valid_Quantity: "Quantity must be a whole number greater than 0"
        },
            price: {
                required: "Price must be entered",
                valid_Price:"Price should be minimum 100"
                
            },
            product_image:{
                required:"please upload product image",
                productImage:"Please select a valid image file (PNG, JPG, JPEG, or SVG)."

            }
           
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element); // Places error messages after the input field
        }
    });
});
</script>
</body>

</html>
