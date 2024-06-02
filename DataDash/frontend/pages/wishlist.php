<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wishlist</title>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>
</head>
<body>
    <h1>Wishlist</h1>
    <div class="topnav">
        <a href="homepage.php">Home</a>
        <?php if (sessionExists()): ?>
            <a href="cart.php">Shopping Cart</a>
            <a href="wishlist.php">Wishlist</a>
        <?php endif; ?>
        <?php if (sessionExists()): ?>
            <a href="../../backend/utils/logout.php">Logout</a>
        <?php else: ?>
            <a href="login_page.php">Login</a>
        <?php endif; ?>
    </div>
    <div class="wishlist-container">
        <table class="wishlist-table">
            <tr>
                <th>Index</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <!-- wishlist items will be displayed here -->
        </table>
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
    <script src="../js/wishlist.js"></script>
</body>
</html>