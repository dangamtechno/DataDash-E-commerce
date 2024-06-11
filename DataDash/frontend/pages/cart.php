<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Check if the user is logged in
if (!sessionExists()) {
    header('Location: login_page.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch cart items for the logged-in user
$user_id = getSessionUserID();
$cart_query = "SELECT cp.product_id, p.name, p.price, cp.quantity
                FROM cart_product cp
                INNER JOIN cart c ON cp.cart_id = c.cart_id
                INNER JOIN product p ON cp.product_id = p.product_id
                WHERE c.user_id = '$user_id'";
$cart_result = $conn->query($cart_query);

// Calculate total price
$total_price = 0;
while ($cart_row = $cart_result->fetch_assoc()) {
    $total_price += $cart_row['price'] * $cart_row['quantity'];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Shopping Cart</h1>
        <div class="topnav">
            <a href="homepage.php">Home</a>
            <?php if (sessionExists()): ?>
                <a href="cart.php">Shopping Cart</a>
            <?php endif; ?>
            <?php if (sessionExists()): ?>
                <a href="../../backend/utils/logout.php">Logout</a>
            <?php else: ?>
                <a href="login_page.php">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <div class="cart-container">
            <?php if ($cart_result->num_rows > 0): ?>
            <table class="cart-table">
                <tr>
                    <th>Index</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
                <?php $index = 1; ?>
                <?php while ($cart_row = $cart_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td><?= $cart_row['name'] ?></td>
                    <td>$<?= $cart_row['price'] ?></td>
                    <td><?= $cart_row['quantity'] ?></td>
                    <td><!-- Add remove button here if needed --></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <div class="cart-total">
                Total: <span id="cart-total">$<?= $total_price ?></span>
            </div>
            <form id="checkout-form" action="confirmation_page.php" method="get">
                 <input type="hidden" name="order_id" id="order_id" value="">
                 <button type="button" class="checkout-button">Checkout</button>
            </form>
            <?php else: ?>
            <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="social-media">
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

    <script src="../js/cart.js"></script>
</body>
</html>