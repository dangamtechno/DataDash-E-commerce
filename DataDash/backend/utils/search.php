<?php
require_once '../../backend/utils/session.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datadash"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the search form has been submitted
if (isset($_GET['submit-search'])) {
    $searchTerm = $_GET['search'];
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

    // Construct the SQL query
    $query = "SELECT * FROM product WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";

    // Execute the query and fetch results
    $results = mysqli_query($conn, $query);

    // Display the results
    if (mysqli_num_rows($results) > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            // ... (HTML to display each product, matching your shop.php)
        }
    } else {
        echo '<p class="no-results">No products found.</p>';
    }
} else {
    // If no search term, return all products
    $results = mysqli_query($conn, "SELECT * FROM product");

    if (mysqli_num_rows($results) > 0) {
        while ($row = mysqli_fetch_assoc($results)) {
            // ... (HTML to display each product, matching your shop.php)
        }
    } else {
        echo '<p class="no-results">No products available.</p>';
    }
}

// Close the database connection
$conn->close();
?>