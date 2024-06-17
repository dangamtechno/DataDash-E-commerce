<?php
require_once '../../backend/utils/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datadash"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if filter has been applied
if (isset($_GET['category'])) {
    $category = $_GET['category'];

    // Sanitize input
    $category = mysqli_real_escape_string($conn, $category);

    // If "All Categories" is selected, retrieve all products
    if ($category === "") {
        $query = "SELECT * FROM product";
    } else {
        // If a category is selected, filter by category
        $query = "SELECT * FROM product WHERE category_id = (SELECT category_id FROM category WHERE category_name = '$category')";
    }

    // Execute the query and fetch results
    $results = mysqli_query($conn, $query);

    // Display the results
    if (mysqli_num_rows($results) > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            // ... (HTML to display each product, matching shop.php) ...
            echo '<div class="product">';
            echo '<a href="product_details.php?id=' . $row['product_id'] . '">';
            echo '<img src="../images/electronic_products/' . $row['image'] . '" alt="' . $row['name'] . '">';
            echo '<h3>' . $row['name'] . '</h3>';
            echo '<p>$' . $row['price'] . '</p>';
            echo '</a>';
            if (sessionExists()) {
                echo '<form action="../../backend/utils/add_to_cart.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
                echo '<input type="hidden" name="quantity" value="1">';
                echo '<button type="submit" class="add-to-cart">Add to Cart</button>';
                echo '</form>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="no-results">No products found in this category.</p>';
    }
} else {
    // Return all products if no category is selected (shouldn't happen)
    $results = mysqli_query($conn, "SELECT * FROM product");

    if (mysqli_num_rows($results) > 0) {
        // ... (HTML to display each product, matching shop.php) ...
    } else {
        echo '<p class="no-results">There are no products available in our store.</p>';
    }
}

// Close the database connection
$conn->close();
?>