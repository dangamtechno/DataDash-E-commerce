<?php
require_once '../utils/session.php';

// Connect to database
// $conn = new mysqli("localhost", "root", "", "datadash");

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert user
function insertUser($first_name, $last_name, $username, $email, $password, $phone = null) {
    global $conn;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (first_name, last_name, username, email, password_hash, phone) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password_hash, $phone);
    $stmt->execute();
    return $conn->insert_id;
}

// Get user by ID
function getUserById($user_id) {
    global $conn;
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Call the functions
if (isset($_POST['insert'])) {
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

    if ($password === $confirm_password) {
        $user_id = insertUser($first_name, $last_name, $username, $email, $password, $phone);
        createSession($user_id);
        header("Location: welcome.php");
        exit;
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

