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
    header('Location: ../../frontend/pages/shop.php'); // Redirect to shop page if product ID is not provided
    exit;
}

$conn = new mysqli("localhost", "root", "", "datadash");

// Prepare statement to get user ID securely
$sql1 = "SELECT user_id FROM users WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id)";
$stmt = $conn->prepare($sql1);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Get the cart ID from the cart table
$cart_id = null;
$sql2 = "SELECT cart_id FROM cart WHERE user_id = '$user_id'";
$result = $conn->query($sql2);
if ($result->num_rows > 0) {
    $cart = $result->fetch_assoc();
    $cart_id = $cart['cart_id'];
} else {
    // If no cart exists, create a new one
    $insert_cart_query = "INSERT INTO cart (user_id) VALUES ('$user_id')";
    $result = $conn->query($insert_cart_query);
    $cart_id = $conn->insert_id;
}

// Check if the product already exists in the cart
$sql3 = "SELECT quantity FROM cart_product WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
$result = $conn->query($sql3);
if ($result->num_rows > 0) {
    $existing_product = $result->fetch_assoc();
    $new_quantity = $existing_product['quantity'] + $quantity;
    $update_query = "UPDATE cart_product SET quantity = '$new_quantity' WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
    $result = $conn->query($update_query);
} else {
    // Product does not exist in the cart, insert a new row
    $insert_query = "INSERT INTO cart_product (cart_id, product_id, quantity) VALUES ('$cart_id', '$product_id', '$quantity')";
    $result = $conn->query($insert_query);
}

if (!$result) {
    echo "Error adding to cart: " . $conn->error;
    exit;
}

$conn->close();

// Redirect to the product details page
header('Location: ../../frontend/pages/product_details.php?id=' . $product_id . '&added=true');
exit;
?>