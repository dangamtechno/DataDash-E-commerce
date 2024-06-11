<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

// Check if the user is logged in
if (!sessionExists()) {
    header('Location: ../../frontend/pages/login_page.php'); // Redirect to login page if not logged in
    exit;
}

// Get the product ID and quantity from the form submission
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

// Check if the product ID is provided and valid
if ($product_id === null) {
    header('Location: ../../frontend.pages/shop.php'); // Redirect to shop page if product ID is not provided
    exit;
}

$conn = new mysqli("localhost", "root", "", "datadash");

// Get the user ID from the session
$user_id = getSessionUserID();

// Get the cart ID from the cart table
$cart_query = "SELECT cart_id FROM cart WHERE user_id = '$user_id'";
$cart_result = $conn->query($cart_query);
if ($cart_result->num_rows > 0) {
    $cart_row = $cart_result->fetch_assoc();
    $cart_id = $cart_row['cart_id'];
} else {
    // Handle the case where the user does not have a cart
    header('Location: ../../frontend/pages/shop.php'); // Redirect to shop page if no cart found
    exit;
}

// Check if the product already exists in the cart
$check_query = "SELECT * FROM cart_product WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
$check_result = $conn->query($check_query);

if ($check_result->num_rows > 0) {
    // Product already exists in the cart, update the quantity
    $update_query = "UPDATE cart_product SET quantity = quantity + $quantity WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
    $conn->query($update_query);
} else {
    // Product does not exist in the cart, insert a new row
    $insert_query = "INSERT INTO cart_product (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', '$quantity')";
    $conn->query($insert_query);
}

$conn->close();

// Redirect to the cart page
//header('Location: ../../frontend/pages/cart.php');
//exit;
?>