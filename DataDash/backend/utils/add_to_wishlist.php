<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

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

// Prepare statement to get user ID securely
$sql1 = "SELECT user_id FROM users WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id)";
$stmt = $conn->prepare($sql1);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Get the wishlist ID from the wishlist table
$wishlist_id = null;
$sql2 = "SELECT wishlist_id FROM wishlist WHERE user_id = '$user_id'";
$result = $conn->query($sql2);
if ($result->num_rows > 0) {
    $wishlist = $result->fetch_assoc();
    $wishlist_id = $wishlist['wishlist_id'];
} else {
    // If no wishlist exists, create a new one
    $insert_wishlist_query = "INSERT INTO wishlist (user_id) VALUES ('$user_id')";
    $result = $conn->query($insert_wishlist_query);
    $wishlist_id = $conn->insert_id;
}


// Check if the product already exists in the wishlist
$sql3 = "SELECT * FROM wishlist_products WHERE wishlist_id = '$wishlist_id' AND product_id = '$product_id'";
$result = $conn->query($sql3);
if ($result->num_rows > 0) {
    // Product already exists in the wishlist
    header('Location: ../../frontend/pages/product_details.php?id=' . $product_id . '&wishlist_exists=true');
    exit;
} else {
    // Product does not exist in the wishlist, insert a new row
    $insert_query = "INSERT INTO wishlist_products (wishlist_id, product_id) VALUES ('$wishlist_id' , '$product_id')";
    $result = $conn->query($insert_query);
}

if (!$result) {
    header('Location: ../../frontend/pages/homepage.php');
    echo "Error adding to wishlist: " . $conn->error;
    exit;
}

$conn->close();

// Redirect to the product details page
header('Location: ../../frontend/pages/wishlist.php');
exit;
?>
