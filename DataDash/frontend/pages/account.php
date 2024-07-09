<?php
require_once '../../backend/utils/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        /* Header */
        header {
            background-color: #232f3e;
            color: #fff;
            border-bottom: 2px solid #febd69
            max-width: 1000px;
            padding-top: -70px; /* Add padding to create space for the header */
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 60px;
        }

        .login-status {
            margin-left: 20px;
            font-size: 14px;
        }

        .navigation ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navigation li {
            margin-right: 20px;
        }

        .navigation a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        /* Main Content */
        main {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }

        section {
            margin-bottom: 40px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
            border-bottom: 2px solid #7909f1;
            padding-bottom: 5px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }

        a {
            color: #0066c0;
            text-decoration: none;
        }

        /* Footer */
        footer {
            background-color: #232f3e;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .social-media ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .social-media li {
            margin: 0 10px;
        }

        .social-media a {
            color: #fff;
            font-size: 20px;
        }

        .general-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .help,
        .legal {
            text-align: left;
        }

        .help ul,
        .legal ul {
            padding: 0;
            list-style-type: none;
        }

        .help h3,
        .legal h3 {
            color: #fff;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .help a,
        .legal a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .location {
            flex-grow: 1;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="heading">
            <div class="left-heading">
                <div class="logo">
                        <img src="../images/misc/DataDash.png" alt="Logo" width="105" height="500">
                </div>
                <div class="login-status">
                    <?php if (sessionExists()): ?>
                        <p class="hello-message">Hello, <?php echo getSessionUsername(); ?>!</p>
                    <?php endif; ?>
                </div>
            </div>
            <nav class="navigation">
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <?php if (sessionExists()): ?>
        <!-- Login and Security -->
            <section>
                <h2><a href="login_and_security.php">Login And Security</a></h2>
            </section>

            <!-- Order History -->
            <section>
                <h2><a href="order_history.php">Order History</a></h2>
            </section>

            <!-- Saved Addresses -->
            <section>
                <h2><a href="saved_addresses.php">Saved Addresses</a></h2>
            </section>

            <!-- Payment Methods -->
            <section>
                <h2><a href="payment_methods.php">Payment Methods</a></h2>
            </section>

            <!-- Wishlist -->
            <section>
                <h2><a href="wishlist.php">Wishlist</a></h2>
            </section>

            <!-- Logout -->
            <a href="../../backend/utils/logout.php">Logout</a>
        <?php else: ?>
            <p>You need to be logged in to access this page.</p>
        <?php endif; ?>
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
            </ul> <br>
                2024 DataDash, All Rights Reserved.
        </div>
    </div>
</footer>
<script src="../js/search.js"></script>
</body>
</html>