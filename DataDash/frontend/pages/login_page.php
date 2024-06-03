<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>

</head>
<body>
    <div class="topnav">
    <a href="homepage.php">Home</a>
    <?php if (sessionExists()): ?>
        <a href="../../backend/utils/logout.php">Logout</a>
    <?php else: ?>
        <a href="login_page.php">Login</a>
        <a href="create_account.php">Create Account</a>
    <?php endif; ?>
    <?php if (sessionExists()): ?>
        <a href="cart.php">Shopping Cart</a>
    <?php endif; ?>
</div>

    <div class="create-account-container login-container">
        <h1>User Login</h1>
        <p>Input your user info and click Submit.</p>

        <form action="../../backend/utils/login.php" method="post">
            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" required style="width: 100%; padding: 10px; margin-bottom: 20px;
             border: 1px solid #ccc;"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Sign in">
        </form>
        <p>Or</p>
        <p>Click here to create an account:</p>
        <a href="create_account.php">
            <button class="create-account">Create Account</button>
        </a>
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
</footer>
</body>
</html>
