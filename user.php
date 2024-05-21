<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert user
function insertUser($first_name, $last_name, $username, $email, $password, $phone = null) {
    $query = "INSERT INTO users (first_name, last_name, username, email, password_hash, phone) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password, $phone);
    $stmt->execute();
    return $conn->insert_id;
}

// Get user by ID
function getUserById($user_id) {
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
    $phone = $_POST['phone'];
    $user_id = insertUser($first_name, $last_name, $username, $email, $password, $phone);
    echo "User inserted with ID: $user_id";
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = getUserById($user_id);
    echo json_encode($user);
}

$conn->close();
?>
