<?php
require './include/db.php';


$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM brands WHERE brand_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "brand deleted successfully"]);
} else {
    echo json_encode(["message" => "Failed to delete brand"]);
}

$stmt->close();
exit();
?>
