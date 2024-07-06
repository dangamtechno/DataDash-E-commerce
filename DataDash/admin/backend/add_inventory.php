<?php
require './include/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$quantity = $data['quantity'];

//product_id needs to be determined from the product name
$product_id = null;
$product_stmt = $conn->prepare("SELECT product_id FROM product WHERE name = ?");
$product_stmt->bind_param("s", $name);
$product_stmt->execute();
$product_result = $product_stmt->get_result();
if ($product_result->num_rows > 0) {
    $product_row = $product_result->fetch_assoc();
    $product_id = $product_row['product_id'];
} else {
    echo json_encode(["message" => "Product not found"]);
    $product_stmt->close();
    exit();
}

$stmt = $conn->prepare("INSERT INTO inventory (product_id, quantity) VALUES (?, ?)");
$stmt->bind_param("ii", $product_id, $quantity);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "Item added successfully"]);
} else {
    echo json_encode(["message" => "Failed to add item"]);
}

$stmt->close();
$product_stmt->close();
exit();
?>
