<?php
$conn = new mysqli("localhost", "root", "", "datadash");

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // Select user_id based on username
    $username = $_POST['user_name'];
    $stmt_select = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt_select->execute([$username]);
    $user_id_result = $stmt_select->get_result();

    if ($user_id_result->num_rows > 0) {
        $user_id = $user_id_result->fetch_assoc()['user_id'];
        $product_id = $_POST['product_id'];
        $review  = $_POST['review'];
        $rating = $_POST['rating'];

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, review_text, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iisi', $product_id, $user_id, $review, $rating);

        if ($stmt->execute()) {
            echo json_encode(['product_review' => true]);
        } else {
            echo json_encode(['error' => 'Something went wrong with the review submission.']);
        }

        $stmt->close();
        exit();
    } else {
        echo json_encode(['error' => 'User not found.']);
        exit();
    }
}
$conn->close();
?>