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

// Get the wishlist ID and new name from the POST request
$wishlist_id = isset($_POST['wishlist_id']) ? intval($_POST['wishlist_id']) : null;
$new_name = isset($_POST['new_name']) ? $conn->real_escape_string($_POST['new_name']) : null;

// Check if the wishlist ID and new name are provided
if ($wishlist_id === null || $new_name === null || $new_name === '') {
    header("Location: ../../frontend/pages/wishlists.php?error=Please provide a valid wishlist ID and a new name");
    exit;
}

// Update the wishlist name in the database
$sql = "UPDATE wishlist SET wishlist_name = ? WHERE wishlist_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $new_name, $wishlist_id, $user_id);

if ($stmt->execute()) {
    // Redirect back to the wishlist page with a success message
    header("Location: ../../frontend/pages/wishlists.php?message=Wishlist renamed successfully");
} else {
    // Redirect back to the wishlist page with an error message
    header("Location: ../../frontend/pages/wishlists.php?error=Error renaming wishlist: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
 