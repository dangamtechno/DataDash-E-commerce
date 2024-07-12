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

// Get the wishlist name from the POST request
$wishlist_name = isset($_POST['wishlist_name']) ? $conn->real_escape_string($_POST['wishlist_name']) : null;

// Check if the wishlist name is provided
if ($wishlist_name === null || $wishlist_name === '') {
    header("Location: ../../frontend/pages/wishlists.php?error=Please provide a wishlist name");
    exit;
}

// Check if the wishlist name already exists and find a unique name
$original_name = $wishlist_name;
$counter = 1;
while (true) {
    $sql = "SELECT COUNT(*) as count FROM wishlist WHERE user_id = ? AND wishlist_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $wishlist_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        break;
    }
    
    $wishlist_name = $original_name . $counter;
    $counter++;
}

// Insert the new wishlist into the database
$sql = "INSERT INTO wishlist (user_id, wishlist_name) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $wishlist_name);

if ($stmt->execute()) {
    // Redirect back to the wishlist page with a success message
    header("Location: ../../frontend/pages/wishlists.php?message=Wishlist added successfully");
} else {
    // Redirect back to the wishlist page with an error message
    header("Location: ../../frontend/pages/wishlists.php?error=Error adding wishlist: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
