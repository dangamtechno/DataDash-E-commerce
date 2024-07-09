<?php
require_once '../../backend/utils/session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!sessionExists()) {
    echo "<p class='error-message'>You must be logged in to reset your password.</p>";
    echo "<a href='login_page.php'>Login</a>";
    exit;
}

// Get user_id from the session
$user_id = $_SESSION['user_id'];

// Get order history from database
$sql = "SELECT * FROM orders WHERE user_id = $user_id";
$result = $conn->query($sql);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Add shadow */
        }

        th, td {
            padding: 12px 15px; /* Increased padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #680eea; /* Purple header */
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Light grey alternate rows */
        }

        /* Heading Styles */
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .social-media ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: inline-block; /* Adjust social media icons to the right */
        }

        .social-media li {
            display: inline-block;
            margin-right: 10px;
        }

        .social-media i {
            margin-right: 5px;
        }

        .general-info {
            display: flex;
            justify-content: space-between; /* Align sections evenly */
            margin-top: 20px;
        }

        .general-info div {
            text-align: left;
            width: 30%; /* Adjust section width */
        }

        .general-info h3 {
            margin-bottom: 10px;
        }

        .general-info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .general-info li {
            margin-bottom: 5px;
        }

        /* Order Items Table Styling */
        .order-items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Add shadow */
        }

        .order-items-table th,
        .order-items-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-items-table th {
            background-color: #f5f5f5; /* Light grey header */
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order History</h2>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo "$" . number_format($row['total_amount'], 2); ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="order-items-table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = new mysqli("localhost", "root", "", "datadash");

                                    // Fetch order items for the current order
                                    $order_id = $row['order_id'];
                                    $sql_order_items = "SELECT p.name AS product_name, oi.quantity, oi.unit_price, oi.status 
                                                        FROM order_items oi
                                                        JOIN product p ON oi.product_id = p.product_id
                                                        WHERE oi.order_id = $order_id";
                                    $result_order_items = $conn->query($sql_order_items);
                                    while ($order_item = $result_order_items->fetch_assoc()):
                                    ?>
                                        <tr>
                                            <td><?php echo $order_item['product_name']; ?></td>
                                            <td><?php echo $order_item['quantity']; ?></td>
                                            <td><?php echo "$" . number_format($order_item['unit_price'], 2); ?></td>
                                            <td><?php echo $order_item['status']; ?></td>
                                        </tr>
                                    <?php endwhile;
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
    </div>

<footer>
    <div class="social-media">
        <br><br>
        <ul>
            <li><a href="#"><i class="fab fa-facebook fa-1.5x"></i>Facebook</a></li>
            <li><a href="#"><i class="fab fa-instagram fa-1.5x"></i>Instagram</a></li>
            <li><a href="#"><i class="fab fa-youtube fa-1.5x"></i>YouTube</a></li>
            <li><a href="#"><i class="fab fa-twitter fa-1.5x"></i>Twitter</a></li>
            <li><a href="#"><i class="fab fa-pinterest fa-1.5x"></i>Pinterest</a></li>
        </ul>
    </div>
    <div class="general-info">
        <div class="help">
            <h3>Help</h3>
            <ul>
                <li><a href="faq.php">Frequently Asked Questions</a></li>
                <li><a href="returns.php">Returns</a></li>
                <li><a href="customer_service.php">Customer Service</a></li>
            </ul>
        </div>
        <div class="location">
            <p>123 Main Street, City, Country</p>
        </div>
        <div class="legal">
            <h3>Privacy & Legal</h3>
            <ul>
                <li><a href="cookies_and_privacy.php">Cookies & Privacy</a></li>
                <li><a href="terms_and_conditions.php">Terms & Conditions</a></li>
            </ul> <br>
                2024 DataDash, All Rights Reserved.
        </div>
    </div>
</footer>
<script src="../js/payment_methods.js"></script>
<script src="../js/search.js"></script>
</body>
</html>