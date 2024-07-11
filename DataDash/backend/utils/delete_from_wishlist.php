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
$wishlist_id = isset($_POST['wishlist_id']) ? $_POST['wishlist_id'] : null;

//var_dump($wishlist_id); // Should print the wishlist ID
//exit; // Exit after printing for testing

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

/*/ Get the wishlist ID from the wishlist table
$sql = "SELECT wishlist_id FROM wishlist WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $wishlist = $result->fetch_assoc();
    $wishlist_id = $wishlist['wishlist_id'];
} else {
    // If no wishlist exists, redirect to shop page
    header('Location: ../../frontend/pages/shop.php'); // Redirect to shop page if no wishlist exists
    exit;
}
*/


// Check if the product exists in the wishlist
$sql = "SELECT * FROM wishlist_products WHERE wishlist_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $wishlist_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: ../../frontend/pages/shop.php'); // Redirect to shop page if product is not in the wishlist
    exit;
} else {
    // Product exists in the wishlist, delete it
    $delete_query = "DELETE FROM wishlist_products WHERE wishlist_id = ? AND product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $wishlist_id, $product_id);

    if (!$stmt->execute()) {
        echo "Error deleting from wishlist: " . $stmt->error;
        exit;
    }
}

$conn->close();

// Redirect to the wishlist page
header('Location: ../../frontend/pages/wishlist.php');
exit;
?>
