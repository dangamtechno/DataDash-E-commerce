<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!sessionExists()) {
    header("Location: ../../frontend/pages/login_page.php");
    exit;
}

// Get user ID from the session
$user_id = getSessionUserId();

// Get the wishlist ID from the POST request
$wishlist_id = isset($_POST['wishlist_id']) ? intval($_POST['wishlist_id']) : null;

// Check if the wishlist ID is provided
if ($wishlist_id === null || $wishlist_id <= 0) {
    header("Location: ../../frontend/pages/wishlist.php?error=Invalid wishlist ID");
    exit;
}

// Check if the wishlist belongs to the user
$sql = "SELECT COUNT(*) as count FROM wishlist WHERE user_id = ? AND wishlist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $wishlist_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    header("Location: ../../frontend/pages/wishlist.php?error=Wishlist not found or does not belong to the user");
    exit;
}

// Delete the wishlist and its associated products
$sql = "DELETE FROM wishlist_products WHERE wishlist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wishlist_id);
$stmt->execute();

$sql = "DELETE FROM wishlist WHERE wishlist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wishlist_id);

if ($stmt->execute()) {
    // Redirect back to the wishlist page with a success message
    header("Location: ../../frontend/pages/wishlists.php?message=Wishlist deleted successfully");
} else {
    // Redirect back to the wishlist page with an error message
    header("Location: ../../frontend/pages/wishlists.php?error=Error deleting wishlist: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
