<?php
require '../include/database_config.php';

// Connect to database
// $conn = new mysqli("localhost", "username", "password", "database_name");



// Display products
echo "<h2>Products</h2>";
echo "<table>";
echo "<tr><th>Name</th><th>Description</th><th>Price</th><th>Action</th></tr>";
$products = $conn->query("SELECT * FROM Products");
while ($product = $products->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $product['name'] . "</td>";
    echo "<td>" . $product['description'] . "</td>";
    echo "<td>" . $product['price'] . "</td>";
    echo "<td><form action='cart.php' method='post'><input type='hidden' name='add' value='" . $product['product_id'] .
        "'><input type='number' name='quantity' value='1'><input type='submit' value='Add to Cart'></form></td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();

