<?php
session_start();

// Function to create a new session
function createSession($user_id) {
    $_SESSION['user_id'] = $user_id;
}

// Function to regenerate the session ID (for security)
function regenerateSession() {
    session_regenerate_id(true);
}

// Function to destroy the session (for logout)
function destroySession() {
    session_unset();
    session_destroy();
}

// Function to check if a session exists
function sessionExists() {
    return isset($_SESSION['user_id']);
}

// Function to get the current session user ID
function getSessionUserID() {
    return $_SESSION['user_id'];
}

// Function to update the session user ID (if needed)
function updateSessionUserID($new_user_id) {
    $_SESSION['user_id'] = $new_user_id;
}
?>
