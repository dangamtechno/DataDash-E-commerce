<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>
</head>
<body>
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
    <div class="cart-container">
        <table class="cart-table">
            <tr>
                <th>Index</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <!-- cart items will be displayed here -->
        </table>
        <div class="cart-total">
            Total: $<span id="cart-total">0.00</span>
        </div>
            <form id="checkout-form" action="confirmation_page.php" method="get">
             <input type="hidden" name="order_id" id="order_id" value="">
             <button type="button" class="checkout-button">Checkout</button>
        </form>
    </div>
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
