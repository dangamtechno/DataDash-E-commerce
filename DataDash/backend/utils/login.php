<?php
require_once 'session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the posted data
$username_or_email = $_POST['username_or_email'];
$password = $_POST['password'];

// Check if the username or email exists
$query = "SELECT * FROM users WHERE (username = ? OR email = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username_or_email, $username_or_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $user_data['password_hash'])) {
        // Create a session and log the user in
        createSession($user_data['user_id']);
        header("Location: ../frontend/index.html");
        exit;
    } else {
        echo "Invalid password.";
    }
} else {
    echo "Invalid username or email.";
}

$conn->close();
?>
