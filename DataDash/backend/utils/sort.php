<?php
require_once '../../backend/utils/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datadash"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if sort order has been selected
if (isset($_GET['sort'])) {
    $sortOrder = $_GET['sort'];

    // Sanitize input
    $sortOrder = mysqli_real_escape_string($conn, $sortOrder);

    // Construct the SQL query based on the sort order
    if ($sortOrder === 'price-asc') {
        $query = "SELECT * FROM product ORDER BY price ASC";
    } elseif ($sortOrder === 'price-desc') {
        $query = "SELECT * FROM product ORDER BY price DESC";
    } elseif ($sortOrder === 'rating') {
        $query = "SELECT * FROM product ORDER BY rating DESC"; // Assuming you have a 'rating' column in your 'product' table
    } else {
        $query = "SELECT * FROM product";
    }

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
            if (sessionExists()) { // Assuming sessionExists() is defined in your session.php
                echo '<form action="../../backend/utils/add_to_cart.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
                echo '<input type="hidden" name="quantity" value="1">';
                echo '<button type="submit" class="add-to-cart">Add to Cart</button>';
                echo '</form>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="no-results">There are no products available in our store.</p>';
    }
} else {
    // Return all products if no sort order is selected
    $results = mysqli_query($conn, "SELECT * FROM product");

    if (mysqli_num_rows($results) > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            echo '<div class="product">';
            echo '<a href="product_details.php?id=' . $row['product_id'] . '">';
            echo '<img src="../images/electronic_products/' . $row['image'] . '" alt="' . $row['name'] . '">';
            echo '<h3>' . $row['name'] . '</h3>';
            echo '<p>$' . $row['price'] . '</p>';
            echo '</a>';
            if (sessionExists()) { // Assuming sessionExists() is defined in your session.php
                echo '<form action="../../backend/utils/add_to_cart.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
                echo '<input type="hidden" name="quantity" value="1">';
                echo '<button type="submit" class="add-to-cart">Add to Cart</button>';
                echo '</form>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="no-results">There are no products available in our store.</p>';
    }
}

// Close the database connection
$conn->close();
?>




