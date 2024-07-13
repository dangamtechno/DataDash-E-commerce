<?php
session_start();

require_once '../utils/session.php';
require_once '../include/database_config.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert user
function insertUser($first_name, $last_name, $username, $email, $password, $favorite_movie, $phone = null) {
    global $conn;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (first_name, last_name, username, email, password_hash, favorite_movie, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $first_name, $last_name, $username, $email, $password_hash, $favorite_movie, $phone);
    $stmt->execute();
    return $conn->insert_id;
}

// Create default wishlist
function createDefaultWishlist($user_id) {
    global $conn;
    $wishlist_name = "Shopping List";
    $query = "INSERT INTO wishlist (user_id, wishlist_name) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $wishlist_name);
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        return false;
    }
}

// Call the functions
if (isset($_POST['insert'])) {
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $favorite_movie = filter_input(INPUT_POST, 'favorite_movie', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

    if ($password === $confirm_password) {
        $user_id = insertUser($first_name, $last_name, $username, $email, $password, $favorite_movie, $phone);
        
        if ($user_id) {
            $wishlist_id = createDefaultWishlist($user_id);
            if ($wishlist_id) {
                createSession($user_id);
                header("Location: ../../frontend/pages/homepage.php");
                exit;
            } else {
                echo "Failed to create a default wishlist.";
            }
        } else {
            echo "Failed to create the user.";
        }
    } else {
        echo "Password and confirm password fields do not match.";
    }
}

$conn->close();
?>
