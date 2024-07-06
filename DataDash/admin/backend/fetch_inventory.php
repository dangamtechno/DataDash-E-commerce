<?php
require './include/db.php';

$stmt = "SELECT inventory.*, product.name AS product_name FROM inventory 
         JOIN product ON inventory.product_id = product.product_id";
$result = $conn->query($stmt);
$inventory = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($inventory);
?>