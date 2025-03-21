<?php
require 'connect.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    die("Please log in to view your orders.");
}

$user_id = $_SESSION["user_id"];

// Validate checkout_id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid checkout ID.");
}

$checkout_id = intval($_GET['id']);

// Prepare SQL query
$stmt = $conn->prepare("
    SELECT 
        p.product_id, 
        p.product_name, 
        p.price AS cost, 
        p.product_image, 
        p.quantity AS available_stock,
        c.quantity AS ordered_qty,
        c.cart_id
    FROM tbl_products p
    INNER JOIN tbl_cart c ON p.product_id = c.product_id
    INNER JOIN tbl_checkout_products cp ON c.cart_id = cp.cart_id
    INNER JOIN tbl_payment py ON cp.checkout_id = py.checkout_id
    WHERE py.user_id = ? AND cp.checkout_id = ?
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

if (!$stmt->bind_param("ii", $user_id, $checkout_id)) {
    die("Binding failed: " . $stmt->error);
}

if (!$stmt->execute()) {
    die("Execution failed: " . $stmt->error);
}

$result = $stmt->get_result();
if (!$result) {
    die("Getting result failed: " . $stmt->error);
}

// Fetch items
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

$stmt->close();

$sql1="SELECT name FROM tbl_user WHERE user_id=$user_id ";
$result= $conn->query($sql1);
if($result->num_rows > 0) {
    $row= $result->fetch_assoc();
}

$sql2="SELECT address FROM tbl_checkout WHERE user_id=$user_id AND checkout_id=$checkout_id";
$result2= $conn->query($sql2);
if($result2->num_rows > 0) {
    $row2= $result2->fetch_assoc();
}
// Invoice data
$invoiceNumber = $checkout_id; // Use checkout_id as invoice number
$invoiceDate = date("d/m/Y");
$customerName = $row['name']; // Fetch from DB if available
$customerAddress = $row2['address']; // Fetch from DB if available$taxNumber = "123456"; // Fetch from DB if needed

// Calculate totals
$subTotal = 0;
foreach ($items as $item) {
    $subTotal += $item["cost"] * $item["ordered_qty"];
}
$taxRate = 0; // 0% tax
$taxAmount = $subTotal * ($taxRate / 100);
$total = $subTotal + $taxAmount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice</title>
<link rel="stylesheet" href="styles.css">
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.invoice-container {
    width: 210mm; /* A4 width */
    max-width: 100%;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
    .header {
        text-align: center;
        background: #e8e8e8;
        padding: 10px;
    }
    .header h1 {
        font-size: 36px;
        color: #5a2d82;
        margin: 0;
    }
    .invoice-details {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
    padding-right: 20px; /* Add right padding */
}

.right {
    text-align: right;
    margin-right: 40px; /* Add more right margin */
}

.totals {
    text-align: right;
    margin-right: 40px; /* Add right margin for totals */
}

    .invoice-details p {
        margin: 5px 0;
    }
    .invoice-table {
        width: 95%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .invoice-table th,
    .invoice-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .invoice-table th {
        background: #f4f4f4;
    }
    .totals p {
        margin: 5px 0;
    }
    .terms {
        margin: 20px 0;
    }
    .terms h3 {
        margin-bottom: 10px;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #888;
    }
    
   .print-button {
    display: block;
    margin: 20px auto;
    padding: 10px 15px;
    background-color: #5a2d82;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    }

    .print-button:hover {
        background-color: #452165;
    }

    @media print {
    @page {
        size: A4 portrait;
        margin: 20mm;
    }

    body {
        background: none;
        margin: 0;
        padding: 0;
    }

    .invoice-container {
        width: 100%;
        box-shadow: none;
        border-radius: 0;
        padding-right: 30px; /* Ensure content doesn't overflow */
    }

    .right,
    .totals {
        margin-right: 50px; /* Ensure right-aligned content stays within boundaries */
    }

    
    .print-button {
        display: none !important; /* Hide the print button when printing */
    }
}
</style>
</head>
<body>


<div class="invoice-container">
    <div class="header">
        <h1>INVOICE</h1>
        <h2>SERENITY STYLES</h2>
    </div>
    <div class="invoice-details">
        <div class="left">
            <p><strong>Invoice to:</strong> <?php echo $customerName; ?></p>
        </div>
        <div class="center">
            <p><strong>Address information:</strong></p>
            <p><?php echo $customerAddress; ?></p>
        </div>
        <div class="right">
            <p><strong>Invoice No:</strong> <?php echo $invoiceNumber; ?></p>
            <p><strong>Date:</strong> <?php echo $invoiceDate; ?></p>
        </div>
    </div>
    <table class="invoice-table">
        <thead>
            <tr>
                <th>ITEM</th>
                <th>COST</th>
                <th>QTY</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item["product_name"]); ?></td>
                <td>Rs. <?php echo number_format($item["cost"], 2); ?></td>
                <td><?php echo $item["ordered_qty"]; ?></td>
                <td>Rs. <?php echo number_format($item["cost"] * $item["ordered_qty"], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="totals">
        <p>Sub Total: Rs. <?php echo number_format($subTotal, 2); ?></p>
        <p>Tax: <?php echo $taxRate; ?>%</p>
        <p><strong>TOTAL: Rs. <?php echo number_format($total, 2); ?></strong></p>
    </div>
    <div class="terms">
        <h3>TERMS & CONDITIONS</h3>
        <p>No returns on opened products. Exchanges within 7 days for unused items with proof of purchase. Not responsible for allergic reactions or price changes.

</p>
    </div>
    <div class="footer">
        <p>www.luxuryrealestate.com</p>
        <p>serenitystyles.online@gmail.com</p>
    </div>

</div>
<button class="print-button" onclick="window.print()" style="margin: 20px auto; display: block; padding: 10px 20px; font-size: 16px;">Print Invoice</button>

</body>
</html>
