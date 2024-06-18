<?php
require_once '../../backend/utils/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datadash";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if filter or sort parameters are set
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$sortOrder = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : '';

// Build the WHERE clause for filtering
$whereClause = '';
if ($category !== '') {
    $whereClause = "WHERE category_id = (SELECT category_id FROM category WHERE category_name = '$category')";
}

// Build the ORDER BY clause for sorting
$orderByClause = '';
if ($sortOrder === 'price-asc') {
    $orderByClause = "ORDER BY price ASC";
} elseif ($sortOrder === 'price-desc') {
    $orderByClause = "ORDER BY price DESC";
} elseif ($sortOrder === 'rating') {
    $orderByClause = "ORDER BY rating DESC";
}

// Combine WHERE and ORDER BY clauses
$query = "SELECT * FROM product $whereClause $orderByClause";

// Execute the query and fetch results
$results = mysqli_query($conn, $query);

// Display the results
if (mysqli_num_rows($results) > 0) {
    while ($row = mysqli_fetch_assoc($results)) {
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
    echo '<p class="no-results">No products found matching the criteria.</p>';
}

$conn->close();
?>