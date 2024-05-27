<?php
require_once 'session.php';

// Connect to database
//$conn = new mysqli("localhost", "username", "password", "database_name");

// Connection below is for xampp
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert user
function insertUser($first_name, $last_name, $username, $email, $password, $phone = null) {
    global $conn; // Add this line
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $query = "INSERT INTO users (first_name, last_name, username, email, password_hash, phone) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password_hash, $phone);
    $stmt->execute();
    return $conn->insert_id;
}

// Get user by ID
function getUserById($user_id) {
    global $conn; // Add this line
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Call the functions
if (isset($_POST['insert'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];

    // Check if password and confirm password fields match
    if ($password === $confirm_password) {
        $user_id = insertUser($first_name, $last_name, $username, $email, $password, $phone);
        echo "User inserted with ID: $user_id";
    } else {
        echo "Password and confirm password fields do not match.";
    }
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = getUserById($user_id);
    echo json_encode($user);
}

$conn->close();
?>
