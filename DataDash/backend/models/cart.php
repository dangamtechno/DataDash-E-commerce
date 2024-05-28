<?php
require_once '../utils/session.php';

// Connect to database
// $conn = new mysqli("localhost", "username", "password", "database_name");

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get cart from database
$cart = array();
$user_id = $_SESSION['user_id']; // assuming you have a user_id in the session
$result = $conn->query("SELECT product_id, quantity FROM cart WHERE user_id = '$user_id'");
while ($row = $result->fetch_assoc()) {
    $cart[$row['product_id']] = $row['quantity'];
}

// Add to cart
if (isset($_POST['add'])) {
    $product_id = $_POST['add'];
    $quantity = $_POST['quantity'];
    if (isset($cart[$product_id])) {
        $new_quantity = $cart[$product_id] + $quantity;
        $conn->query("UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'");
    } else {
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
    }
}

// Update cart
if (isset($_POST['update'])) {
    $product_id = $_POST['update'];
    $quantity = $_POST['quantity'];
    $conn->query("UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'");
}

// Remove from cart
if (isset($_POST['remove'])) {
    $product_id = $_POST['remove'];
    $conn->query("DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
}

// Display cart
echo "<h2>Shopping Cart</h2>";
echo "<table>";
echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";
$total = 0;
foreach ($cart as $product_id => $quantity) {
    $product = $conn->query("SELECT * FROM product WHERE id = '$product_id'")->fetch_assoc();
    $price = $product['price'];
    $total += $price * $quantity;
    echo "<tr>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>" . $price . "</td>";
    echo "<td>" . $price * $quantity . "</td>";
    echo "<td><form action='' method='post'><input type='hidden' name='update' value='" . $product_id . "'><input 
    type='number' name='quantity' value='" . $quantity . "'><input type='submit' value='Update'></form><form action='' 
    method='post'><input type='hidden' name='remove' value='" . $product_id . "'><input type='submit' 
    value='Remove'></form></td>";
    echo "</tr>";
}
echo "<tr><th colspan='3'>Total</th><th>" . $total . "</th><th></th></tr>";
echo "</table>";

$conn->close();

