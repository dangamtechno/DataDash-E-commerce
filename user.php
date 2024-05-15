<?php
// Insert customer
function insertUser($name, $email, $password, $address) {
    $query = "INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $email, $password, $address);
    $stmt->execute();
    return $conn->insert_id;
}

// Get customer by ID
function getUserById($user_id) {
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>
