<?php
include 'connect.php';

// Security check to prevent unauthorized access
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if service_id is provided in the query string
if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']);

    // Fetch the service details from the database
    $sql = "SELECT * FROM tbl_services WHERE service_id = $service_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Service not found!</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Invalid service ID!</div>";
    exit();
}

// Fetch categories from the database
$sql_categories = "SELECT catservice_id, cat_name FROM tbl_category_services WHERE status='active'";
$result_categories = $conn->query($sql_categories);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST["service_name"];
    $service_description = $_POST["service_description"];
    $category_service = $_POST["category_service"];
    $service_price = $_POST["service_price"];

     // Handle file upload
    
    if(!empty($_FILES["service_image"])){
        $target_dir = "images/service_images/"; //location of the image where it stored
        $target_file = $target_dir . basename($_FILES["service_image"]["name"]);
        move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file); //move uploaded file to the directory
    }
 else{
    $target_file=$service['service_image'];
 }

    // Update service details in the database
    $sql_update = "UPDATE tbl_services 
                   SET service_name = '$service_name', 
                       service_description = '$service_description', 
                       catservice_id = '$category_service',
                       price='$service_price',
                       service_image='$target_file'
                   WHERE service_id = $service_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<div class='alert alert-success'>Service updated successfully, redirecting to service list</div>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'viewservices.php';
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
    <title>Edit Service</title>
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
            <li><a href="addservices.php">Services</a></li>
            <li><a href="addproducts.php">Products</a></li>
            <li><a href="viewappointments.php">Appointments</a></li>
            <li><a href="viewuser.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Edit Service</h2>

    <div class="form-container">
        <h5>Edit Service</h5>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

        <form method="post" id="service_form"action="" enctype="multipart/form-data">
            <input type="text" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" placeholder="Service Name" required>
            
            <!-- Dropdown for categories -->
            <select name="category_service" required>
                <option value="" disabled>Select Category</option>
                <?php
                if ($result_categories->num_rows > 0) {
                    while ($row = $result_categories->fetch_assoc()) {
                        $selected = ($row['catservice_id'] == $service['catservice_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['catservice_id']) . '" ' . $selected . '>' . htmlspecialchars($row['cat_name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No categories available</option>';
                }
                ?>
            </select>

            <textarea name="service_description" placeholder="Description" rows="4" required><?php echo htmlspecialchars($service['service_description']); ?></textarea>
            <input type="number" name="service_price" value="<?php echo htmlspecialchars($service['price']); ?>" placeholder="price" required>
            <div class="service_images">
        <img id="image_preview" class="image-preview" src="<?php echo htmlspecialchars($service['service_image']); ?>" style="max-width: 200px; max-height: 200px;">
           
            </div>
             <!-- File input for product image -->
     
    <input type="file" name="service_image" accept="image/*" required onchange="previewImage(event)">
    
            <div>
            <button type="submit">Update Service</button>

            </div>
            
        </form>
       
    </div>
    </div>
    
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

    jQuery.validator.addMethod('serviceImage', function (value, element) {
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
    $('#service_form').validate({
        rules: {
            service_name: {
                required: true,
                lettersonly: true,
                minlength: 3
            },
            category: {
                required: true
            },
            service_description: {
                required: true,
                minlength: 10
            },
            quantity: {
                required: true,
                valid_Quantity: true
            },
            service_price: {
                required: true,
                valid_Price: true
            },
            service_image: {
                serviceImage: true // Custom image validation
            }
        },
        messages: {
            service_name: {
                required: "Please enter the product name",
                lettersonly: "Only letters are allowed",
                minlength: "Product name must be at least 3 characters long"
            },
            category_service: {
                required: "Please select a category"
            },
            service_description: {
                required: "Please enter a description",
                minlength: "Description should be at least 10 characters long"
            },
            quantity: {
                required: "Please enter a quantity",
                valid_Quantity: "Quantity must be a whole number greater than 0"
            },
            service_price: {
                required: "Please enter the price",
                valid_Price: "Price must be a positive number"
            },
            service_image: {
                serviceImage: "Only image files (PNG, JPG, JPEG, or SVG) are allowed."
            }
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element); // Places error messages after the input field
        }
    });

    // Trigger validation when a file is selected
    $('#service_image').on('change', function () {
        $('#service_form').validate().element(this);
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

</html>
