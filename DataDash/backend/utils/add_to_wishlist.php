<?php
require_once '../../backend/utils/session.php';

// Check if the user is logged in
if (!sessionExists()) {
    header('Location: ../../frontend/pages/login_page.php'); // Redirect to login page if not logged in
    exit;
}

// Get the product ID and wishlist ID from the form submission
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$wishlist_id = isset($_POST['wishlist_id']) ? $_POST['wishlist_id'] : null;

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

// Check if the wishlist ID is provided and valid, if not create a new wishlist
if ($wishlist_id === 'select') {
    header('Location: ../../frontend/pages/product_details.php?id=' . $product_id . '&wishlist_exists=true'); 
} else {
    // Validate the provided wishlist ID belongs to the user
    $sql = "SELECT wishlist_id FROM wishlist WHERE wishlist_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $wishlist_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If the wishlist does not belong to the user, redirect to shop
        header('Location: ../../frontend/pages/cart.php');
        exit;
    }
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
    echo "Product already exist in the wishlist";
    exit;
} else {
    // Product does not exist in the wishlist, insert a new row
    $insert_query = "INSERT INTO wishlist_products (wishlist_id, product_id, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iii", $wishlist_id, $product_id, $user_id);
    if ($stmt->execute()) {
        header('Location: ../../frontend/pages/wishlist_details.php?wishlist_id=' . $wishlist_id);
    } else {
        echo "Error adding to wishlist: " . $conn->error;
    }
    exit;
}

$conn->close();
?>
