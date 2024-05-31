<?php

require '../include/database_config.php';

// Fetch order details
$orderID = $_GET['order_id']; // Assuming order ID is passed as a query parameter

$query = $pdo->prepare("
    SELECT orders.order_id, orders.total_amount, orders.order_date, 
           product.name, product.price, order_details.quantity
    FROM orders
    JOIN order_details ON orders.order_id = order_details.order_id
    JOIN product ON order_details.product_id = product.product_id
    WHERE orders.order_id = :orderID
");
$query->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$query->execute();
$orderDetails = $query->fetchAll(PDO::FETCH_ASSOC);

include 'confirmation_page.php';
