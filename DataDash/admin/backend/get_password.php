<?php
require './include/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve email and phone from POST data
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepare SQL statement
    $stmt = "SELECT * FROM admin WHERE email = ? AND phone = ?";
    $prep_stmt = $conn->prepare($stmt);

    // Bind parameters
    $prep_stmt->bind_param('ss', $email, $phone);

    // Execute the prepared statement
    if ($prep_stmt->execute()) {
        // Get result
        $result = $prep_stmt->get_result();

        // Check if a row was found
        if ($result->num_rows > 0) {
            // Fetch the password (assuming it's stored in the 'password' column)
            $password = $result->fetch_assoc()['password'];

            // Output the password as JSON
            echo json_encode(['password' => $password]);
        } else {
            // No matching row found
            echo json_encode(['error' => 'No matching record found']);
        }
    } else {
        // Error executing the prepared statement
        echo json_encode(['error' => 'Execution error']);
    }

    // Close the prepared statement
    $prep_stmt->close();
}




?>