<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>
    <style>
        .create-account-container input[type="email"],
        .create-account-container input[type="tel"] {
          width: 100%;
          height: 30px;
          padding: 10px;
          margin: 10px 0;
          box-sizing: border-box;
          border: 1px solid #ccc;
        }
    </style>
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

<div class="create-account-container">
    <h1>Create Account</h1>
    <p>Input your user info and click Submit.</p>

    <form action="../../backend/models/user.php" method="POST" id="create-account-form">
        <label for="first-name">First Name:</label>
        <input type="text" id="first-name" name="first_name" required><br>
        <label for="last-name">Last Name:</label>
        <input type="text" id="last-name" name="last_name" required><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="password" required><br>
        <label for="confirm-pass">Confirm Password:</label>
        <input type="password" id="confirm-pass" name="confirm_password" required><br>
        <label for="fav-movie">Favorite Movie:</label>
        <input type="text" id="fav-movie" name="favorite_movie" required><br>
        <label for="phone">Phone (Optional):</label>
        <input type="tel" id="phone" name="phone"><br><br>
        <input class="submit" type="submit" name="insert" value="Submit">
        <span id="password-mismatch-error" style="color: red; display: none;">Passwords do not match.</span>
    </form>
</div>

<script>
    const form = document.getElementById('create-account-form');
    const passwordInput = document.getElementById('pass');
    const confirmPasswordInput = document.getElementById('confirm-pass');
    const passwordMismatchError = document.getElementById('password-mismatch-error');

    form.addEventListener('submit', function(event) {
        if (passwordInput.value !== confirmPasswordInput.value) {
            event.preventDefault(); // Prevent form submission
            passwordMismatchError.style.display = 'block'; // Show error message
        } else {
            passwordMismatchError.style.display = 'none'; // Hide error message
        }
    });
</script>

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
<script src="../js/search.js"></script>
</body>
</html>