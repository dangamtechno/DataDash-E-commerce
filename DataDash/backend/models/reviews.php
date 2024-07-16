<?php
$conn = new mysqli("localhost", "root", "", "datadash");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID, product ID, rating, and review text from the POST data
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Sanitize the input data (important for security)
    $user_id = intval($user_id); // Convert to integer
    $product_id = intval($product_id); // Convert to integer
    $rating = intval($rating); // Convert to integer (rating is now an integer 1-5)
    $review_text = htmlspecialchars($review_text, ENT_QUOTES, 'UTF-8'); // Escape HTML entities

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (?, ?, ?, ?)");

    // Bind the parameters to the statement
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $review_text);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect the user back to the product details page with a success message
        header('Location: ../../frontend/pages/product_details.php?id=' . $product_id . '&review=success');
        exit;
    } else {
        // Handle the error - redirect with an error message
        header('Location: product_details.php?id=' . $product_id . '&review=error');
        exit;
    }

    // Close the statement
    $stmt->close();
}
$conn->close();
?>