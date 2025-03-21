<?php
include 'connect.php';

// Fetch categories from the database
$sql = "SELECT catservice_id, cat_name FROM tbl_category_services WHERE status='active'";
$result = $conn->query($sql);

// Create the options for the dropdown
echo '<option value="" disabled selected>Select Category</option>';

if ($result->num_rows > 0) {
    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['catservice_id']) . '">' . htmlspecialchars($row['cat_name']) . '</option>';
    }
} else {
    echo '<option value="" disabled>No categories available</option>';
}
?>