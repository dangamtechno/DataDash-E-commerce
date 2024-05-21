<?php
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
?>
