<?php
require './include/db.php';


$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$quantity = $data['quantity'];

$stmt = $conn->prepare("UPDATE inventory SET quantity = ? WHERE product_id = ?");
$stmt->bind_param("ii", $quantity, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "Quantity updated successfully"]);
} else {
    echo json_encode(["message" => "Failed to update quantity"]);
}

$stmt->close();
exit();
?>
