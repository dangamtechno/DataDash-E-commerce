<?php
require './include/db.php';


$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM inventory WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "Item deleted successfully"]);
} else {
    echo json_encode(["message" => "Failed to delete item"]);
}

$stmt->close();
exit();
?>
