<?php
require './include/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];



$stmt = $conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
$stmt->bind_param("s", $name);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["message" => "brand added successfully"]);
} else {
    echo json_encode(["message" => "Failed to add item"]);
}

$stmt->close();
exit();
?>
