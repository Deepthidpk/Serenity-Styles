<?php
include 'connect.php';


// Security check to prevent unauthorized access
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Fetch categories from the database
$sql = "SELECT catservice_id, cat_name FROM tbl_category_services WHERE status='active'";
$result = $conn->query($sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST["service_name"] ?? '';
    $service_description = $_POST["service_description"] ?? '';
    $category_service = $_POST["category_service"] ?? '';
    $service_price = $_POST["service_price"] ?? '';

    // Handle file upload
    $target_dir = "images/service_images/"; //location of the image where it stored
    $target_file = $target_dir . basename($_FILES["service_image"]["name"]);
    move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file); //move uploaded file to the directory
   

   
    // Insert into database only if image upload is successful or not required
    $sql = "INSERT INTO tbl_services (service_name, service_description, catservice_id, price, service_image)
            VALUES ('$service_name', '$service_description', '$category_service', '$service_price', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New service added successfully, redirecting to service list</div>";
        echo "<script>setTimeout(function() { window.location.href = 'viewservices.php'; }, 3000);</script>";
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
            <li><a href="addservices.php">Services</a></li>
            <li><a href="addproducts.php">Products</a></li>
            <li><a href="viewappointments.php">Appointments</a></li>
            <li><a href="viewuser.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Services</h2>

        <!-- Add New Product Form -->
        <div class="form-container">
            <h5>Add New Service</h5>
            <form method="post" id="service_form"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <input type="text" name="service_name" id="service_name"placeholder="service Name" required>
                <!-- Dropdown for categories -->
        <select name="category_service" id="category_service"required>
            <option value="" disabled selected>Select Category</option>
            <?php
           

            
            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['catservice_id']) . '">' . htmlspecialchars($row['cat_name']) . '</option>';
                }
            } else {
                echo '<option value="" disabled>No categories available</option>';
            }

           
            ?>
        </select>
                <textarea name="service_description" placeholder="Description" rows="4" required></textarea>
                <input type="number" name="service_price" id="category_service"placeholder="price" required>
                <div class="preview">
         <!-- Image preview -->
    <img id="image_preview" src="#" alt="Image Preview" style="display: none; max-width: 200px; margin-top: 10px;">
     <!-- File input for product image -->
     
     <input type="file" name="service_image" accept="image/*" required onchange="previewImage(event)">
    
    </div>
                <div>
                <button type="submit">Add Service</button>

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

        jQuery.validator.addMethod('valid_Price', function (value, element) {
            return /^[+]?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value) && parseFloat(value) >= 100;
        }, "Price should be minimum 100");

        jQuery.validator.addMethod('serviceImage', function (value, element, param) {
            var extension = value.substring(value.lastIndexOf('.') + 1).toLowerCase();
            return extension === 'png' || extension === 'jpg' || extension === 'jpeg' || extension === 'svg';
        }, "Please select a valid image file (PNG, JPG, JPEG, or SVG).");

        // ✅ Custom validation for maximum price per category
        jQuery.validator.addMethod('maxPricePerCategory', function (value, element) {
            var category = $('#category_service').val();
            var price = parseFloat(value);
            var maxPrices = {
                '1': 1200,
                '2': 20000,
                '3': 5000,
               '4': 50000
            };
            return price <= (maxPrices[category] || Infinity); // Default to no limit if category not found
        }, "Price exceeds the maximum allowed for the selected category.");

        // 💡 Form validation rules and messages
        $('#service_form').validate({
            rules: {
                service_name: {
                    required: true,
                    lettersonly: true,
                    minlength: 3,
                    remote: {     //built in function in jquery  
                    url: "check_service.php", 
                    type: "POST",
                    data: {
                        username: function() {  // input for check_email.php 
                            return $("#service_name").val();
                        }
                    }
                }
                },
                category_service: {
                    required: true
                },
                service_description: {
                    required: true
                },
                service_price: {
                    required: true,
                    valid_Price: true,
                    maxPricePerCategory: true
                },
                service_image: {
                    required: true,
                    serviceImage: true
                }
            },
            messages: {
                service_name: {
                    required: "Please enter your full name",
                    lettersonly: "Name must be in alphabets only",
                    remote:"Service already exist !"
                },
                category_service: {
                    required: "Please select a category"
                },
                service_description: {
                    required: "Service description is required"
                },
                service_price: {
                    required: "Price must be entered",
                    valid_Price: "Price should be minimum 100",
                    maxPricePerCategory: "Price exceeds the maximum allowed for the selected category."
                },
                service_image: {
                    required: "Please upload service image",
                    serviceImage: "Please select a valid image file (PNG, JPG, JPEG, or SVG)."
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element); // Places error messages after the input field
            }
        });

        // 🔄 Revalidate price field when category changes
        $('#category_service').change(function () {
            $('#service_price').valid();
        });
    });
</script>

</body>

</html>
