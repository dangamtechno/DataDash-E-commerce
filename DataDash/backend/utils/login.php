<?php
session_start();

// Include the functions file
require_once 'session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sign in a user
function signInUser($username_or_email, $password) {
    global $conn; // Use the global $conn variable

    // Prepare the SQL query
    $query = "SELECT user_id, username, password_hash FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $username = $row['username'];
        $stored_password_hash = $row['password_hash'];

        // Verify the password
        if (password_verify($password, $stored_password_hash)) {
            // Create a new session
            createSession($user_id);

            // Return the user's information
            $stmt->close(); // Close the statement here
            return array(
                'user_id' => $user_id,
                'username' => $username
            );
        } else {
            // Invalid password
            $stmt->close(); // Close the statement here
            return null;
        }
    } else {
        // User not found
        $stmt->close(); // Close the statement here
        return null;
    }
}

// Get the posted data
$username_or_email = htmlspecialchars($_POST['username_or_email']);
$password = htmlspecialchars($_POST['password']);

if (!empty($username_or_email) && !empty($password)) {
    // Sign in the user
    $user = signInUser($username_or_email, $password);

    if ($user !== null) {
        // Sign-in successful
        header("Location: ../../frontend/html/homepage.php");
        exit;
    } else {
        echo "Invalid username/email or password.";
    }
} else {
    echo "Please fill in both fields.";
}

$conn->close();
?>