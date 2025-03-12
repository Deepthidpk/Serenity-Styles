<?php
include 'connect.php';

// Security check to prevent unauthorized access
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if product_id is provided in the query string
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM tbl_products WHERE product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Product not found!</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Invalid product ID!</div>";
    exit();
}

// Fetch categories from the database
$sql_categories = "SELECT category_id, category_name FROM tbl_category WHERE status='available'";
$result_categories = $conn->query($sql_categories);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $pro_description = $_POST["pro_description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];

    // Handle file upload
   
    if(!empty($_FILES["product_image"])){
        $target_dir = "images/product_images/"; //location of the image where it stored
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file); //move uploaded file to the directory
    }
 else{
    $target_file=$product['product_image'];
 }

    // Update product details in the database
    $sql_update = "UPDATE tbl_products 
                   SET product_name = '$product_name', 
                       pro_description = '$pro_description', 
                       price = '$price', 
                       quantity = '$quantity', 
                       category_id = '$category',
                       product_image='$target_file'

                   WHERE product_id = $product_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<div class='alert alert-success'>Product updated successfully, redirecting to product list</div>";
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
    <title>Edit Product</title>
    
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

        .form-container {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            margin: 50px auto;
            max-width: 600px;
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
    <h2>Edit Product</h2>

    <div class="form-container">
        <h5>Edit Product</h5>
        <!-- Include jQuery and jQuery Validation Plugin -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<form id="product_form" method="post" action="" enctype="multipart/form-data">
    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" placeholder="Product Name" required>

    <!-- Dropdown for categories -->
    <select name="category" required>
        <option value="" disabled selected>Select Category</option>
        <?php
        if ($result_categories->num_rows > 0) {
            while ($row = $result_categories->fetch_assoc()) {
                $selected = ($row['category_id'] == $product['category_id']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($row['category_id']) . '" ' . $selected . '>' . htmlspecialchars($row['category_name']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>No categories available</option>';
        }
        ?>
    </select>

    <textarea name="pro_description" placeholder="Description" rows="4" required><?php echo htmlspecialchars($product['pro_description']); ?></textarea>

    <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" placeholder="Quantity" required>
    <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="Price" step="0.01" required>

    <div class="product_image">
        <img id="image_preview" class="image-preview" 
             src="<?php echo !empty($product['product_image']) ? htmlspecialchars($product['product_image']) : ''; ?>" 
             style="max-width: 200px; max-height: 200px;">
    </div>
<div>
    <!-- File input for product image -->
    <input type="file" id="product_image" name="product_image" accept="image/*" onchange="previewImage(event)">
    </div>
    <button type="submit">Update Product</button>

</form>

<script>
$(document).ready(function () {
    // Custom validation methods
    jQuery.validator.addMethod('lettersonly', function (value, element) {
        return /^[A-Za-z\s]+$/.test(value);
    }, "Please enter letters only.");

    jQuery.validator.addMethod('valid_Quantity', function (value, element) {
        return /^[1-9]\d*$/.test(value);
    }, "Quantity must be a positive whole number.");

    jQuery.validator.addMethod('valid_Price', function (value, element) {
        return /^[+]?\d+(\.\d+)?$/.test(value) && parseFloat(value) > 0;
    }, "Price must be greater than 0.");
    
    jQuery.validator.addMethod('valid_Productmaxprice', function (value, element) {
    return parseFloat(value) <10000;
}, "Price should be maximum 10000");

    jQuery.validator.addMethod('productImage', function (value, element) {
        // Allow form submission if no new image is uploaded (use existing image)
        if (element.files.length === 0 && $("#image_preview").attr("src") !== '') {
            return true;
        }
        if (element.files.length === 0) {
            return false; // No file selected and no existing image
        }

        var file = element.files[0];
        var extension = file.name.split('.').pop().toLowerCase();
        var allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        var allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];

        return allowedExtensions.includes(extension) && allowedMimeTypes.includes(file.type);
    }, "Only image files (PNG, JPG, JPEG, or SVG) are allowed.");

    // Validate form
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
                required: true,
                minlength: 10
            },
            quantity: {
                required: true,
                valid_Quantity: true
            },
            price: {
                required: true,
                number:true,
                valid_Price:true,
                valid_Productmaxprice:true


            },
            product_image: {
                productImage: true // Custom image validation
            }
        },
        messages: {
            product_name: {
                required: "Please enter the product name",
                lettersonly: "Only letters are allowed",
                minlength: "Product name must be at least 3 characters long"
            },
            category: {
                required: "Please select a category"
            },
            pro_description: {
                required: "Please enter a description",
                minlength: "Description should be at least 10 characters long"
            },
            quantity: {
                required: "Please enter a quantity",
                valid_Quantity: "Quantity must be a whole number greater than 0"
            },
            price: {
                required: "Price must be entered",
                number:"Enter a valid price",
                valid_Price:"Price should be minimum 100",
                valid_Productmaxprice:"Price should be maximum 10000"

            },
            product_image: {
                productImage: "Only image files (PNG, JPG, JPEG, or SVG) are allowed."
            }
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element); // Places error messages after the input field
        }
    });

    // Trigger validation when a file is selected
    $('#product_image').on('change', function () {
        $('#product_form').validate().element(this);
    });
});

// Image preview function
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</body>

</html>
