<?php
$conn = new mysqli("localhost", "root", "", "datadash");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $product_id = $_GET['product_id'];

    $sql = "SELECT reviews.*, users.first_name, users.last_name 
            FROM reviews 
            JOIN users ON reviews.user_id = users.user_id 
            WHERE reviews.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    echo json_encode($reviews);

    $stmt->close();
}
$conn->close();
?>