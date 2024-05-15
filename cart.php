<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get cart from session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Add to cart
if (isset($_POST['add'])) {
    $product_id = $_POST['add'];
    $quantity = $_POST['quantity'];
    if (isset($cart[$product_id])) {
        $cart[$product_id] += $quantity;
    } else {
        $cart[$product_id] = $quantity;
    }
    $_SESSION['cart'] = $cart;
}

// Update cart
if (isset($_POST['update'])) {
    $product_id = $_POST['update'];
    $quantity = $_POST['quantity'];
    $cart[$product_id] = $quantity;
    $_SESSION['cart'] = $cart;
}

// Remove from cart
if (isset($_POST['remove'])) {
    $product_id = $_POST['remove'];
    unset($cart[$product_id]);
    $_SESSION['cart'] = $cart;
}

// Display cart
echo "<h2>Shopping Cart</h2>";
echo "<table>";
echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Action</th></tr>";
$total = 0;
foreach ($cart as $product_id => $quantity) {
    $product = $conn->query("SELECT * FROM Products WHERE product_id = '$product_id'")->fetch_assoc();
    $price = $product['price'];
    $total += $price * $quantity;
    echo "<tr>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>" . $price . "</td>";
    echo "<td>" . $price * $quantity . "</td>";
    echo "<td><form action='' method='post'><input type='hidden' name='update' value='" . $product_id . "'><input type='number' name='quantity' value='" . $quantity . "'><input type='submit' value='Update'></form><form action='' method='post'><input type='hidden' name='remove' value='" . $product_id . "'><input type='submit' value='Remove'></form></td>";
    echo "</tr>";
}
echo "<tr><th colspan='3'>Total</th><th>" . $total . "</th><th></th></tr>";
echo "</table>";

$conn->close();
?>
