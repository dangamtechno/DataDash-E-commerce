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
        <a href="../../backend/utils/logout.php">Logout</a>
    <?php else: ?>
        <a href="login_page.php">Login</a>
    <?php endif; ?>
    <?php if (sessionExists()): ?>
        <a href="cart.php">Shopping Cart</a>
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
        <button class="checkout-button">Checkout</button>
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
                    <li><a href="#">Frequently asked Questions</a></li>
                    <li><a href="#">Delivery Information</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Customer Service</a></li>
                </ul>
            </div>
            <div class="location"></div>
            <div class="legal">
                <h3>Privacy & legal</h3>
                <ul>
                    <li><a href="#">Cookies & Privacy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <script src="../js/cart.js"></script>
</body>
</html>
