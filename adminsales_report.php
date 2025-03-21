<?php
include 'connect.php';



if (!isset($_SESSION['username'])) { // Checks if the user is logged in
    header('Location: login.php'); // Redirects to login.php if the user is not logged in
    exit(); // It's good practice to call exit() after header to stop further script execution
}

// Initialize variables
$reportType = isset($_GET['reportType']) ? $_GET['reportType'] : '';
$reportTitle = '';
$reportData = [];

// Function to get current month's best-selling products
function getBestSellingProducts($conn) {
    $month = date('m');
    $year = date('Y');
    
    $sql = "SELECT 
                p.product_name, 
                SUM(c.quantity) AS total_sold,
                SUM(c.quantity * p.price) AS revenue
            FROM 
                tbl_cart c
            JOIN 
                tbl_products p ON c.product_id = p.product_id
            JOIN 
                tbl_checkout_products cp ON c.cart_id = cp.cart_id
            JOIN 
                tbl_checkout co ON cp.checkout_id = co.checkout_id
            WHERE 
                MONTH(co.checkout_date) = ? AND YEAR(co.checkout_date) = ?
            GROUP BY 
                p.product_name
            ORDER BY 
                total_sold DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Return an empty array if no results to avoid errors
    if ($result->num_rows === 0) {
        return [];
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get previous month's sales
function getPreviousMonthSales($conn) {
    $prevMonth = date('m', strtotime('-1 month'));
    $year = date('Y');
    
    $sql = "SELECT 
                DATE(c.checkout_date) AS sale_date,
                COUNT(DISTINCT c.checkout_id) AS num_orders,
                SUM(p.amount) AS total_sales
            FROM 
                tbl_checkout c
            JOIN 
                tbl_payment p ON c.checkout_id = p.checkout_id
            WHERE 
                MONTH(c.checkout_date) = ? 
                AND YEAR(c.checkout_date) = ?
                AND p.status = 'Success'
            GROUP BY 
                DATE(c.checkout_date)
            ORDER BY 
                sale_date";
              
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $prevMonth, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sales = [];
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
    
    return $sales;
}

// Function to get category sales
function getCategorySales($conn) {
    $sql = "SELECT 
                c.category_name,
                COUNT(cp.chk_id) AS num_items_sold,
                SUM(cp.quantity) AS total_quantity,
                SUM(cp.quantity * p.price) AS revenue
            FROM 
                tbl_checkout_products cp
            JOIN 
                tbl_products p ON cp.product_id = p.product_id
            JOIN 
                tbl_category c ON p.category_id = c.category_id
            JOIN 
                tbl_checkout co ON cp.checkout_id = co.checkout_id
            JOIN 
                tbl_payment pa ON co.checkout_id = pa.checkout_id
            WHERE 
                pa.status = 'Success'
            GROUP BY 
                c.category_id
            ORDER BY 
                revenue DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

// Function to get monthly sales trend
function getMonthlySalesTrend($conn) {
    $sql = "SELECT 
                YEAR(c.checkout_date) AS year,
                MONTH(c.checkout_date) AS month,
                COUNT(DISTINCT c.checkout_id) AS num_orders,
                SUM(p.amount) AS total_sales
            FROM 
                tbl_checkout c
            JOIN 
                tbl_payment p ON c.checkout_id = p.checkout_id
            WHERE 
                p.status = 'Success'
                AND c.checkout_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY 
                YEAR(c.checkout_date), MONTH(c.checkout_date)
            ORDER BY 
                year, month";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $trend = [];
    while ($row = $result->fetch_assoc()) {
        $row['month_name'] = date('F', mktime(0, 0, 0, $row['month'], 10));
        $trend[] = $row;
    }
    
    return $trend;
}

// Generate report based on selected type
if (!empty($reportType)) {
    switch ($reportType) {
        case 'best_selling':
            $reportTitle = 'Best Selling Products (Current Month)';
            $reportData = getBestSellingProducts($conn);
            break;
        case 'previous_month':
            $reportTitle = 'Previous Month Sales';
            $reportData = getPreviousMonthSales($conn);
            break;
        case 'category_sales':
            $reportTitle = 'Sales by Category';
            $reportData = getCategorySales($conn);
            break;
        case 'monthly_trend':
            $reportTitle = 'Monthly Sales Trend (Last 12 Months)';
            $reportData = getMonthlySalesTrend($conn);
            break;
        default:
            $reportTitle = 'Invalid Report Type';
            $reportData = [];
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Sidebar Styles */
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

        .sidebar-header h3 {
            font-size: 20px;
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

        /* Main Content Styles */
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

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card h3 {
            margin-bottom: 10px;
        }

        /* Table Styles */
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

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }

        /* Report Container Styles */
        .report-container {
            background-color: rgba(45, 45, 45, 0.9);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            min-height: 400px;
        }

        .report-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #3d3d3d;
            background-color: #1a1a1a;
            color: #fff;
            width: 300px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .chart-container {
            height: 300px;
            margin-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            font-style: italic;
            color: #aaa;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
                color: black;
            }

            .sidebar, .header, .report-actions, .btn {
                display: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .report-container {
                background-color: white;
                color: black;
            }

            th, td {
                border-bottom: 1px solid #ddd;
            }
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
            <li><?php echo "Hii, " . $_SESSION['username']; ?></li>
            <li><a href="admindashboard.php">Dashboard</a></li>
            <li><a href="viewservices.php">Services</a></li>
            <li><a href="viewproducts.php">Products</a></li>
            <li><a href="manage_appointment.php">Appointments</a></li>
            <li><a href="viewuser.php">Users</a></li>
            <li><a href="viewreview.php">Reviews</a></li>
            <li><a href="sales_report.php">Sales Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Sales Reports</h2>
        </div>

        <div class="report-container">
            <div class="report-actions">
                <form action="" method="GET" id="reportForm">
                    <select name="reportType" id="reportType" onchange="this.form.submit()">
                        <option value="">Select Report Type</option>
                        <option value="best_selling" <?php echo ($reportType == 'best_selling') ? 'selected' : ''; ?>>Best Selling Products (Current Month)</option>
                        <option value="previous_month" <?php echo ($reportType == 'previous_month') ? 'selected' : ''; ?>>Previous Month Sales</option>
                        <option value="category_sales" <?php echo ($reportType == 'category_sales') ? 'selected' : ''; ?>>Sales by Category</option>
                        <option value="monthly_trend" <?php echo ($reportType == 'monthly_trend') ? 'selected' : ''; ?>>Monthly Sales Trend</option>
                    </select>
                </form>
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="printReport()">Print Report</button>
                    <button class="btn btn-success" onclick="exportToCSV()">Export CSV</button>
                </div>
            </div>

            <?php if (!empty($reportType)): ?>
                <h3><?php echo $reportTitle; ?></h3>
                
                <?php if (empty($reportData)): ?>
                    <div class="no-data">No data available for this report.</div>
                <?php else: ?>
                    <?php if ($reportType == 'best_selling'): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Total Quantity Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><?php echo $product['total_sold']; ?></td>
                                        <td>$<?php echo number_format($product['revenue'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="chart-container">
                            <canvas id="productChart"></canvas>
                        </div>
                    <?php elseif ($reportType == 'previous_month'): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Number of Orders</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $day): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($day['sale_date'])); ?></td>
                                        <td><?php echo $day['num_orders']; ?></td>
                                        <td>$<?php echo number_format($day['total_sales'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    <?php elseif ($reportType == 'category_sales'): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Items Sold</th>
                                    <th>Total Quantity</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $category): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                        <td><?php echo $category['num_items_sold']; ?></td>
                                        <td><?php echo $category['total_quantity']; ?></td>
                                        <td>$<?php echo number_format($category['revenue'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    <?php elseif ($reportType == 'monthly_trend'): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Number of Orders</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $month): ?>
                                    <tr>
                                        <td><?php echo $month['month_name'] . ' ' . $month['year']; ?></td>
                                        <td><?php echo $month['num_orders']; ?></td>
                                        <td>$<?php echo number_format($month['total_sales'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="chart-container">
                            <canvas id="trendChart"></canvas>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-data">Select a report type from the dropdown above to generate a report.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to handle printing
        function printReport() {
            window.print();
        }

        // Function to export table data to CSV
        function exportToCSV() {
            const table = document.querySelector('table');
            if (!table) {
                alert('No data to export');
                return;
            }

            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const rowData = [];
                const cols = row.querySelectorAll('td, th');
                
                cols.forEach(col => {
                    // Remove $ and commas from numbers
                    let text = col.innerText.replace(/\$/g, '').replace(/,/g, '');
                    rowData.push('"' + text + '"');
                });
                
                csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', document.title + ' - ' + '<?php echo $reportTitle; ?>.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        // Initialize charts only if we have data
        <?php if (!empty($reportType) && !empty($reportData)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if ($reportType == 'best_selling'): ?>
                    // Best selling products chart
                    const productLabels = <?php echo json_encode(array_column($reportData, 'product_name')); ?>;
                    const productData = <?php echo json_encode(array_column($reportData, 'total_sold')); ?>;
                    
                    // Create product chart safely
                    const productChartEl = document.getElementById('productChart');
                    if (productChartEl) {
                        new Chart(productChartEl, {
                            type: 'bar',
                            data: {
                                labels: productLabels,
                                datasets: [{
                                    label: 'Units Sold',
                                    data: productData,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: 'white'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: 'white'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'white'
                                        }
                                    }
                                }
                            }
                        });
                    }
                <?php elseif ($reportType == 'previous_month'): ?>
                    // Previous month sales chart
                    const dateLabels = <?php echo json_encode(array_map(function($day) {
                        return date('M d', strtotime($day['sale_date']));
                    }, $reportData)); ?>;
                    const salesData = <?php echo json_encode(array_column($reportData, 'total_sales')); ?>;
                    
                    // Create sales chart safely
                    const salesChartEl = document.getElementById('salesChart');
                    if (salesChartEl) {
                        new Chart(salesChartEl, {
                            type: 'line',
                            data: {
                                labels: dateLabels,
                                datasets: [{
                                    label: 'Daily Sales',
                                    data: salesData,
                                    fill: false,
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    tension: 0.1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: 'white',
                                            callback: function(value) {
                                                return '$' + value;
                                            }
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: 'white'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'white'
                                        }
                                    }
                                }
                            }
                        });
                    }
                <?php elseif ($reportType == 'category_sales'): ?>
                    // Category sales chart
                    const categoryLabels = <?php echo json_encode(array_column($reportData, 'category_name')); ?>;
                    const revenueData = <?php echo json_encode(array_column($reportData, 'revenue')); ?>;
                    
                    // Create category chart safely
                    const categoryChartEl = document.getElementById('categoryChart');
                    if (categoryChartEl) {
                        new Chart(categoryChartEl, {
                            type: 'pie',
                            data: {
                                labels: categoryLabels,
                                datasets: [{
                                    data: revenueData,
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.6)',
                                        'rgba(54, 162, 235, 0.6)',
                                        'rgba(255, 206, 86, 0.6)',
                                        'rgba(75, 192, 192, 0.6)',
                                        'rgba(153, 102, 255, 0.6)',
                                        'rgba(255, 159, 64, 0.6)',
                                        'rgba(255, 99, 255, 0.6)',
                                        'rgba(54, 255, 235, 0.6)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(255, 99, 255, 1)',
                                        'rgba(54, 255, 235, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            color: 'white'
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                if (context.parsed !== null) {
                                                    label += '$' + parseFloat(context.parsed).toFixed(2);
                                                }
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                <?php elseif ($reportType == 'monthly_trend'): ?>
                    // Monthly trend chart
                    const monthLabels = <?php echo json_encode(array_map(function($month) {
                        return $month['month_name'] . ' ' . $month['year'];
                    }, $reportData)); ?>;
                    const trendData = <?php echo json_encode(array_column($reportData, 'total_sales')); ?>;
                    const orderData = <?php echo json_encode(array_column($reportData, 'num_orders')); ?>;
                    
                    // Create trend chart safely
                    const trendChartEl = document.getElementById('trendChart');
                    if (trendChartEl) {
                        new Chart(trendChartEl, {
                            type: 'bar',
                            data: {
                                labels: monthLabels,
                                datasets: [
                                    {
                                        label: 'Total Sales',
                                        data: trendData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Number of Orders',
                                        data: orderData,
                                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                        borderColor: 'rgba(153, 102, 255, 1)',
                                        borderWidth: 1,
                                        type: 'line',
                                        yAxisID: 'y1'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Sales ($)',
                                            color: 'white'
                                        },
                                        ticks: {
                                            color: 'white',
                                            callback: function(value) {
                                                return '$' + value;
                                            }
                                        }
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Orders',
                                            color: 'white'
                                        },
                                        ticks: {
                                            color: 'white'
                                        },
                                        grid: {
                                            drawOnChartArea: false
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: 'white'
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'white'
                                        }
                                    }
                                }
                            }
                        });
                    }
                <?php endif; ?>
            });
        <?php endif; ?>
    </script>
</body>

</html>