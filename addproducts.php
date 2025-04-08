<?php
include 'connect.php';

// Security check to prevent unauthorized access
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Add new category via AJAX
if (isset($_POST['ajax_add_category'])) {
    $new_category_name = $_POST['new_category_name'];

    // Insert the new category into the database
    $insert_category_sql = "INSERT INTO tbl_category (category_name, status) VALUES ('$new_category_name', 'available')";

    if ($conn->query($insert_category_sql) === TRUE) {
        $new_id = $conn->insert_id; // Get the ID of the newly inserted category

        // Return the new category data as JSON for the dropdown update
        echo json_encode([
            'success' => true,
            'message' => 'Category added successfully',
            'id' => $new_id,
            'name' => $new_category_name
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding category: ' . $conn->error]);
        exit;
    }
}

// Regular form processing for adding categories (non-AJAX fallback)
if (isset($_POST['add_category'])) {
    $new_category_name = $_POST['new_category_name'];

    // Insert the new category into the database
    $insert_category_sql = "INSERT INTO tbl_category (category_name, status) VALUES ('$new_category_name', 'available')";

    if ($conn->query($insert_category_sql) === TRUE) {
        echo "<div class='alert alert-success'>New category added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding category: " . $conn->error . "</div>";
    }
}

// Fetch categories from the database
$sql = "SELECT category_id, category_name FROM tbl_category WHERE status='available'";
$result = $conn->query($sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_name"])) {
    $product_name = $_POST["product_name"];
    $pro_description = $_POST["pro_description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];

    // Handle file upload
    $target_dir = "images/product_images/"; //location of the image where it stored
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file); //move uploaded file to the directory

    $sql = "INSERT INTO tbl_products (product_name, pro_description, price, quantity, category_id, product_image)
            VALUES ('$product_name', '$pro_description', '$price', '$quantity', '$category', '$target_file')";

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

        /* Category button styles */
        .category-actions {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .add-category-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }

        .add-category-btn:hover {
            background-color: #0069d9;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #2d2d2d;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #3d3d3d;
            border-radius: 5px;
            width: 50%;
            color: #fff;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .alert {
            margin-top: 10px;
            margin-bottom: 10px;
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

        <!-- Status message area for feedback -->
        <div id="status-message"></div>

        <!-- Add New Product Form -->
        <div class="form-container">
            <h5>Add New Product</h5>
            <form method="post" id="product_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                enctype="multipart/form-data">
                <input type="text" name="product_name" id="product_name" placeholder="Product Name" required>

                <!-- Category selection with Add New button -->
                <div class="category-actions">
                    <select name="category" id="category" required style="width: calc(100% - 120px);">
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
                    <button type="button" class="add-category-btn" id="openModalBtn">Add New Category</button>
                </div>

                <textarea name="pro_description" placeholder="Description" rows="4" required></textarea>
                <input type="number" name="quantity" placeholder="Quantity" required>
                <input type="number" name="price" id="product_price" placeholder="Price" step="0.01" required>
                <div class="preview">
                    <!-- Image preview -->
                    <img id="image_preview" src="#" alt="Image Preview"
                        style="display: none; max-width: 200px; margin-top: 10px;">
                    <!-- File input for product image -->
                    <input type="file" name="product_image" accept="image/*" required onchange="previewImage(event)">
                </div>
                <div>
                    <button type="submit">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4>Add New Category</h4>
            <form method="post" id="category_form">
                <input type="text" name="new_category_name" id="new_category_name" placeholder="Category Name" required>
                <button type="submit" id="submit_category">Add Category</button>
            </form>
            <div id="modal-status-message"></div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script>
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

        // Modal functionality
        var modal = document.getElementById("categoryModal");
        var btn = document.getElementById("openModalBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function () {
            modal.style.display = "block";
        }

        span.onclick = function () {
            modal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $(document).ready(function () {
            // Add category form submission with AJAX
            $("#category_form").submit(function (e) {
                e.preventDefault();

                var categoryName = $("#new_category_name").val();

                if (categoryName.trim() === '') {
                    $("#modal-status-message").html('<div class="alert alert-danger">Category name cannot be empty</div>');
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
                    data: {
                        ajax_add_category: true,
                        new_category_name: categoryName
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            // Add the new category to the dropdown directly
                            var newOption = new Option(response.name, response.id);
                            $("#category").append(newOption);

                            // Select the newly added category
                            $("#category").val(response.id);

                            // Show success message
                            $("#modal-status-message").html('<div class="alert alert-success">' + response.message + '</div>');

                            // Clear the form
                            $("#new_category_name").val("");

                            // Close the modal after 1.5 seconds
                            setTimeout(function () {
                                modal.style.display = "none";
                                $("#modal-status-message").html('');
                            }, 1500);
                        } else {
                            // Show error message
                            $("#modal-status-message").html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function () {
                        $("#modal-status-message").html('<div class="alert alert-danger">Error adding category. Please try again.</div>');
                    }
                });
            });

            // Custom validation methods
            jQuery.validator.addMethod('lettersonly', function (value, element) {
                return /^[^-\s][a-zA-Z_\s-]+$/.test(value);
            }, "Please use letters only.");

            jQuery.validator.addMethod('valid_Quantity', function (value, element) {
                return /^[+]?\d+$/.test(value) && parseInt(value, 10) > 0;
            }, "Quantity should be a positive whole number greater than 0");

            jQuery.validator.addMethod('valid_Productprice', function (value, element) {
                return parseFloat(value) >= 100;
            }, "Price should be minimum 100");

            jQuery.validator.addMethod('valid_Productmaxprice', function (value, element) {
                return parseFloat(value) < 10000;
            }, "Price should be maximum 10000");

            jQuery.validator.addMethod('productImage', function (value, element, param) {
                var extension = value.substring(value.lastIndexOf('.') + 1).toLowerCase();
                return extension === 'png' || extension === 'jpg' || extension === 'jpeg' || extension === 'svg';
            }, "Please select a valid image file (PNG, JPG, JPEG, or SVG).");

            // Form validation rules and messages
            $('#product_form').validate({
                rules: {
                    product_name: {
                        required: true,
                        lettersonly: true,
                        minlength: 3,
                        remote: {
                            url: "check_product.php",
                            type: "POST",
                            data: {
                                username: function () {
                                    return $("#product_name").val();
                                }
                            }
                        }
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
                        number: true,
                        valid_Productprice: true,
                        valid_Productmaxprice: true
                    },
                    product_image: {
                        required: true,
                        productImage: true
                    }
                },
                messages: {
                    product_name: {
                        required: "Please enter your full name",
                        lettersonly: "Name must be in alphabets only",
                        remote: "Product already exists!"
                    },
                    category: {
                        required: "Please select a category"
                    },
                    pro_description: {
                        required: "Product description is required"
                    },
                    quantity: {
                        required: "Please enter a quantity",
                        valid_Quantity: "Quantity must be a whole number greater than 0"
                    },
                    price: {
                        required: "Price must be entered",
                        number: "Enter a valid price",
                        valid_Productprice: "Price should be minimum 100",
                        valid_Productmaxprice: "Price should be maximum 10000"
                    },
                    product_image: {
                        required: "Please upload product image",
                        productImage: "Please select a valid image file (PNG, JPG, JPEG, or SVG)."
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });

            // Add validation for category form
            $('#category_form').validate({
                rules: {
                    new_category_name: {
                        required: true,
                        lettersonly: true,
                        minlength: 3
                    }
                },
                messages: {
                    new_category_name: {
                        required: "Please enter a category name",
                        lettersonly: "Category name must be alphabets only",
                        minlength: "Category name must be at least 3 characters"
                    }
                }
            });

            $('#product_price').change(function () {
                $('#product_price').valid();
            });
        });
    </script>
</body>

</html>