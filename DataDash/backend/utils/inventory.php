<?php
require '../include/database_config.php';
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $stmt = "SELECT p.name, i.location, i.quantity, i.reorder_level, i.last_updated_date
             FROM product p
             JOIN inventory i ON p.product_id = i.product_id";

    // Add filters or conditions if needed
    if (isset($_GET['id'])) {
        $stmt .= " WHERE p.product_id = ?";
        $prep_stmt = $conn->prepare($stmt);
        $id = $_GET['id'];
        $prep_stmt->bind_param('i', $id);
    } else {
        $prep_stmt = $conn->prepare($stmt);
    }

    $prep_stmt->execute();
    $result = $prep_stmt->get_result();

    if ($result) {
        $inventory_data = array();
        while ($row = $result->fetch_assoc()) {
            $inventory_data[] = $row;
        }
        echo json_encode($inventory_data);
    } else {
        echo json_encode(['error' => "Something went wrong"]);
    }
}
$conn->close();
