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

    <script src="../js/cart.js"></script>
</body>
</html>
