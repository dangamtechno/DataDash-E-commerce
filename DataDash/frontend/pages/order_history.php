<?php
require_once '../../backend/utils/session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user_id from the session
$user_id = $_SESSION['user_id'];

// Get order history from database
$sql = "SELECT * FROM order_history WHERE user_id = $user_id";
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
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
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
            justify-content: space-around;
            margin-top: 20px;
        }

        .general-info div {
            text-align: left;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Order History</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Current Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td><?php echo $row['total_amount']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['current_status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
<footer>
    <a href="account.php">
        <button style="background-color: #680eea; color: #fff; padding: 10px 20px; border: none; border-radius: 5px;
         cursor: pointer;">Back to Account</button>
    </a>
    <div class="social-media">
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
            </ul>
        </div>
    </div>
     2024 DataDash, All Rights Reserved.
</footer>
<script src="../js/payment_methods.js"></script>
</body>
</html>