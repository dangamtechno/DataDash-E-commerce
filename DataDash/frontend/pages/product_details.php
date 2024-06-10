<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Retrieve the product ID from the query parameter
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if the product ID is provided and valid
if ($product_id === null) {
    header('Location: shop.php'); // Redirect to shop page if product ID is not provided
    exit;
}

// Query the database to fetch product details
$product = $conn->query("SELECT p.product_id, p.category_id, p.brand_id, p.name, p.description, p.price, p.image,
                    p.status, p.date_added, c.category_name, b.brand_name FROM product p LEFT JOIN 
                    category c ON p.category_id = c.category_id LEFT JOIN brands b ON p.brand_id = b.brand_id
                    WHERE p.product_id = '$product_id'");

// Check if the product exists
if ($product->num_rows > 0) {
    $product_data = $product->fetch_assoc();
} else {
    header('Location: shop.php'); // Redirect to shop page if product not found
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5, minimum-scale=1.0">
    <script src="https://kit.fontawesome.com/d0ce752c6a.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>Product Details - DataDash</title>
</head>
<body>
<header>
    <div class="heading">
        <div class="left-heading">
            <div class="logo">
                <a href="homepage.php">
                    <img src="../images/DataDash.png" alt="Logo" width="85" height="500">
                </a>
            </div>
            <div class="search-bar">
                <form class="search-form">
                    <label>
                        <input type="search" name="search" placeholder="search...">
                    </label>
                    <input type="submit" name="submit-search" class="search-button">
                </form>
            </div>
        </div>
        <div class="right-heading">
            <div class="login-status">
                <?php if (sessionExists()): ?>
                    <div class="hello-message">
                        <span>Hello, <?php echo getSessionUsername(); ?></span>
                    </div>
                    <div class="icons">
                        <a href="account.php"><i class="fas fa-user-check fa-2x"></i>Account</a>
                        <a href="cart.php"><i class="fas fa-shopping-cart fa-2x"></i>Cart</a>
                        <a href="../../backend/utils/logout.php"><i class="fas fa-sign-out-alt fa-2x"></i>Logout</a>
                    </div>
                <?php else: ?>
                    <div class="login" title="login">
                        <a href="login_page.php"><i class="fas fa-sign-in-alt fa-2x"></i>Login</a>
                    </div>
                    <div class="register" title="register">
                        <a href="create_account.php"><i class="fas fa-user-times fa-2x"></i>Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main>
    <section class="product-details">
        <h2><?= $product_data['name'] ?></h2>
        <img src="../images/<?= $product_data['image'] ?>" alt="<?= $product_data['name'] ?>">
        <p>Price: $<?= $product_data['price'] ?></p>
        <p>Description: <?= $product_data['description'] ?></p>
        <p>Category: <?= $product_data['category_name'] ?></p>
        <p>Brand: <?= $product_data['brand_name'] ?></p>
        <button>Add to Cart</button>
    </section>
</main>

<footer>
    <div class="social-media">
        <br><br>
        <ul>
            <li><a href="#"><i class="fab fa-facebook fa-1.5x"></i>Facebook</a></li>
            <li><a href="#"><i class="fab fa-instagram fa-1.5x"></i>Instagram</a></li>
            <li><a href="#"><i class="fab fa-youtube fa-1.5x"></i>YouTube</a></li>
            <li><a href="#"><i class="fab fa-twitter fa-1.5x"></i>Twitter</a></li>
            <li><a href="#"><i class="fab fa-pinterest fa-1.5x"></i>Pinterest</a></li>
        </ul>
    </div>
    <div class="general-info">
        <div class="help">
            <h3>Help</h3>
            <ul>
                <li><a href="faq.php">Frequently Asked Questions</a></li>
                <li><a href="returns.php">Returns</a></li>
                <li><a href="customer_service.php">Customer Service</a></li>
            </ul>
        </div>
        <div class="location">
            <p>123 Main Street, City, Country</p>
        </div>
        <div class="legal">
            <h3>Privacy & Legal</h3>
            <ul>
                <li><a href="cookies_and_privacy.php">Cookies & Privacy</a></li>
                <li><a href="terms_and_conditions.php">Terms & Conditions</a></li>
            </ul>
        </div>
    </div>
    2024 DataDash, All Rights Reserved.
</footer>
<script src="../js/navbar.js"></script>
</body>
</html>