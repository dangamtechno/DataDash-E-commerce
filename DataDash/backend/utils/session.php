<?php
session_start();

// Function to create a new session
function createSession($user_id) {
    $conn = new mysqli("localhost", "root", "", "datadash");
    $session_id = bin2hex(random_bytes(16)); // Generate a random session ID
    $query = "INSERT INTO sessions (session_id, user_id, start_time) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $session_id, $user_id);
    $stmt->execute();
    $_SESSION['session_id'] = $session_id; // Store the session ID in the PHP session
    $conn->close();
}

// Function to destroy the session (for logout)
function destroySession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    } else {
        // Handle session not active error
        trigger_error("Session is not active", E_USER_WARNING);
    }
}

// Function to check if a session exists
function sessionExists() {
    return isset($_SESSION['user_id']);
    // return session_status() === PHP_SESSION_ACTIVE;

}

// Function to get the current session user ID
function getSessionUserID() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $query = "SELECT user_id FROM sessions WHERE session_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $_SESSION['session_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
        }
        $stmt->close();
        $conn->close();
        return $user_id ?? null;
    }
    return null;
}

// Function to update the session user ID (if needed)
function updateSessionUserID($new_user_id) {
    if (sessionExists()) {
        $_SESSION['user_id'] = $new_user_id;
    } else {
        // Handle session not existing error
        trigger_error("Session does not exist", E_USER_WARNING);
    }
}

function getSessionUsername() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $user_id = getSessionUserID();
        $query = "SELECT username FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username'];
        }
        $stmt->close();
        $conn->close();
        return $username ?? null;
    }
    return null;
}

