<?php
session_start();

require_once 'session.php';

// Connect to database
//$conn = new mysqli("localhost", "username", "password", "database_name");

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order history from database
$user_id = $_SESSION['user_id']; // assuming you have a user_id in the session
$result = $conn->query("SELECT * FROM order_history WHERE user_id = '$user_id'");

// Display order history
echo "<h2>Order History</h2>";
echo "<table>";
echo "<tr><th>Order ID</th><th>Order Date</th><th>Total Amount</th><th>Status</th><th>Current Status</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['order_id'] . "</td>";
    echo "<td>" . $row['order_date'] . "</td>";
    echo "<td>" . $row['total_amount'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "<td>" . $row['current_status'] . "</td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();

