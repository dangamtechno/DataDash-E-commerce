<?php
require './include/db.php';


$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$name = $data['name'];

$stmt = $conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
$stmt->bind_param("si", $name, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "name updated successfully"]);
} else {
    echo json_encode(["message" => "Failed to update quantity"]);
}

$stmt->close();
exit();
?>
