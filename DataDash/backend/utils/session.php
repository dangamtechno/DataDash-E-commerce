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
    $_SESSION['user_id'] = $user_id; //test
    $conn->close();
}

// Function to destroy the session (for logout)
function destroySession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $session_id = $_SESSION['session_id'];
        session_unset();
        session_destroy();

        $conn = new mysqli("localhost", "root", "", "datadash");
        if ($conn->connect_error) {
            // Handle database connection error
            return false;
        }

        $stmt = $conn->prepare("DELETE FROM sessions WHERE session_id = ?");
        $stmt->bind_param("s", $session_id);
        if (!$stmt->execute()) {
            // Handle query execution error
            return false;
        }

        $stmt->close();
        $conn->close();
        return true;
    } else {
        return false;
    }
}

// Function to check if a session exists
function sessionExists() {
  // Check if the PHP session is active
  if (!isset($_SESSION)) {
    return false;
  }

  // Initialize the "session_id" key in the $_SESSION array
  if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
  }

  // Get the session ID from the PHP session
  $session_id = $_SESSION['session_id'];

  $conn = new mysqli("localhost", "root", "", "datadash");

  // Check if the session exists in the sessions table
  $query = "SELECT * FROM sessions WHERE session_id = '$session_id'";
  $result = mysqli_query($conn, $query);

  // Check if a user_id in the users table matches the user_id foreign key in the sessions table
  if (mysqli_num_rows($result) > 0) {
    $session_data = mysqli_fetch_assoc($result);
    $user_id = $session_data['user_id'];
    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      return true;
    }
  }

  return false;
}


// Function to get the current session user ID
function getSessionUserID() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $query = "SELECT user_id FROM sessions WHERE session_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $_SESSION['session_id']); // Use $_SESSION['session_id'] instead of session_id()
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

