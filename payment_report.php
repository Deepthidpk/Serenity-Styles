<?php
include('connect.php');


// Check if user_id exists in the session
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to the login page or show an error
    echo "Please log in to view your payment details.";
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user name (assuming you need this for the report)
$sql1 = "SELECT name FROM tbl_user WHERE user_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$row1 = $result1->fetch_assoc();

// Check if the GET request has the checkout ID
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Get checkout_id from the GET request
    $checkout_id = $_GET['id'];

    // Prepare the SQL query to get the payment details based on checkout_id and user_id
    $sql = "SELECT * FROM tbl_payment WHERE checkout_id = ? AND user_id = ? AND status = 'Success'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $checkout_id, $user_id);
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();
    
    // Check if a record was found
    if ($result->num_rows == 0) {
        echo "No payment details found for this checkout.";
        exit(); // Stop further execution if no records are found
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request or missing checkout ID.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .report-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        h2 {
            margin-bottom: 10px;
        }
        .company-details {
            text-align: left;
            font-size: 14px;
        }
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .payment-table th, .payment-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .payment-table th {
            background-color: #f2f2f2;
        }
        .print-btn {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 10px; /* Adjust font size for print */
            }
            .report-container {
                width: 100%;
                text-align: left;
            }
            .payment-table {
                width: 100%;
                font-size: 10px; /* Adjust table font size for printing */
                margin: 0;
            }
            .payment-table th, .payment-table td {
                padding: 6px; /* Reduce padding for better space utilization */
            }
            .company-details {
                font-size: 12px;
                margin-bottom: 10px;
            }
            @page {
                size: A4 portrait; /* Force portrait orientation for printing */
                margin: 20mm; /* Set margins for better utilization of space */
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>

<div class="report-container">
    <h2>Payment Report</h2>
    <div class="company-details">
        <p><strong>Company Name:</strong> Serenity Styles</p>
        <p><strong>Address:</strong> Hill top Street, Nilambur, Malappuram, Kerala, India</p>
        <p><strong>Date:</strong> <?php echo date("Y-m-d"); ?></p>
    </div>
    
    <table class="payment-table">
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><strong>Customer Name</strong></td>
            <td><?php echo htmlspecialchars($row1['name']); ?></td>
        </tr>
        <tr>
            <td><strong>Payment ID</strong></td>
            <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
        </tr>
        <tr>
            <td><strong>Amount</strong></td>
            <td><?php echo htmlspecialchars($row['amount']); ?></td>
        </tr>
        <tr>
            <td><strong>Payment Date</strong></td>
            <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
        </tr>
    <?php } ?>
</table>


    <button class="print-btn" onclick="window.print()">Print Report</button>
</div>

</body>
</html>
