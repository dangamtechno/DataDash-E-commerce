<?php
require_once '../../backend/utils/session.php';

// Check if the user is logged in
if (!sessionExists()) {
    header('Location: ../../frontend/pages/login_page.php'); // Redirect to login page if not logged in
    exit;
}

// Get the product ID from the form submission
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;

// Check if the product ID is provided and valid
if ($product_id === null) {
    header('Location: ../../frontend/pages/shop.php'); // Redirect to shop page if product ID is not provided
    exit;
}

$conn = new mysqli("localhost", "root", "", "datadash");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the session
$user_id = getSessionUserId();

if (!$user_id) {
    header('Location: ../../frontend/pages/login_page.php');
    exit;
}

// Get the wishlist ID or create a new wishlist
$sql = "SELECT wishlist_id FROM wishlist WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $wishlist = $result->fetch_assoc();
    $wishlist_id = $wishlist['wishlist_id'];
} else {
    // If no wishlist exists, create a new one
    $insert_wishlist_query = "INSERT INTO wishlist (user_id) VALUES (?)";
    $stmt = $conn->prepare($insert_wishlist_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $wishlist_id = $conn->insert_id;
}

// Check if the product already exists in the wishlist
$sql = "SELECT * FROM wishlist_products WHERE wishlist_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $wishlist_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Product already exists in the wishlist
    header('Location: ../../frontend/pages/product_details.php?id=' . $product_id . '&wishlist_exists=true');
    exit;
} else {
    // Product does not exist in the wishlist, insert a new row
    $insert_query = "INSERT INTO wishlist_products (wishlist_id, product_id, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iii", $wishlist_id, $product_id, $user_id);
    if ($stmt->execute()) {
        header('Location: ../../frontend/pages/wishlist.php');
    } else {
        echo "Error adding to wishlist: " . $conn->error;
    }
    exit;
}

$conn->close();
?>
