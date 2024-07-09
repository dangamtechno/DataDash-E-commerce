<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5,minimum-scale=1.0">
    <script src="https://kit.fontawesome.com/d0ce752c6a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>
	<title>Terms & Conditions</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
		}
		h1, h2 {
			color: #333;
			margin-top: 30px;
		}
		p {
			line-height: 1.5;
			margin-bottom: 10px;
		}
		ol {
			margin-left: 20px;
		}
		.back-to-home {
			display: inline-block;
			padding: 10px 20px;
			background-color: #333;
			color: #fff;
			text-decoration: none;
			border-radius: 5px;
			margin-top: 20px;
		}

        .shop-button-container {
        text-align: center; /* Center the button horizontally */
        margin-top: 10px; /* Add some space above the button */
        }

        .shop-button {
            display: inline-block;
            padding: 10px 40px;
            font-size: 16px;
            color: #fff;
            background-color: #009dff; /* Bootstrap primary color */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .shop-button:hover {
            background-color: #0056b3; /* Darker shade for hover effect */
        }
	</style>
</head>
<body>
<header>
        <div class="heading">
            <div class="left-heading">
                <div class="logo">
                    <a href="homepage.php">
                        <img src="../images/misc/DataDash.png" alt="Logo" width="105" height="500">
                    </a>
                </div>
                <div class="search-bar">
                    <form id="search-form" method="GET" action="shop.php">
                        <label>
                            <input type="search" name="search" id="search-input" placeholder="search...">
                        </label>
                        <input type="submit" value="Search">
                    </form>
                </div>
            </div> <br>
            <div class="shop-button-container">
                <a href="shop.php" class="shop-button">Shop</a>
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
	<h1>Terms & Conditions</h1>

	<p>Welcome to our e-commerce website. By accessing and using our website, you agree to be bound by the following terms and conditions. Please read them carefully before using our services.</p>

	<h2>1. Acceptance of Terms</h2>
	<p>By accessing, browsing, or using our website, you acknowledge that you have read, understood, and agreed to be bound by these Terms & Conditions, as well as our Cookies & Privacy Page. If you do not agree with any part of these terms, please refrain from using our website or services.</p>

	<h2>2. Product Information</h2>
	<p>We strive to provide accurate and up-to-date information about our products, including descriptions, pricing, and availability. However, we reserve the right to make changes or corrections to this information at any time without prior notice. Please contact our customer service team if you have any questions or concerns regarding product information.</p>

	<h2>3. Ordering and Payment</h2>
	<p>By placing an order on our website, you represent that you are of legal age to enter into a binding contract and that all information provided during the checkout process is accurate and complete. We reserve the right to cancel or refuse any order at our discretion.</p>
	<p>Payment for orders must be made in full at the time of purchase. We accept various payment methods, as outlined on our website. If your payment is declined or fails for any reason, we reserve the right to cancel or suspend your order.</p>

	<h2>4. Shipping and Delivery</h2>
	<p>We will make reasonable efforts to deliver your order within the estimated timeframe provided during the checkout process. However, we cannot guarantee specific delivery dates or times. Delivery times may vary depending on your location, the shipping method chosen, and other factors beyond our control.</p>
	<p>Risk of loss and title for the products pass to you upon delivery to the shipping address provided during the checkout process.</p>

	<h2>5. Returns and Refunds</h2>
	<p>Our return and refund policy is outlined on our Returns page. Please review this policy carefully before making a purchase. We reserve the right to modify or update our return and refund policy at any time without prior notice.</p>

	<h2>6. Intellectual Property</h2>
	<p>All content, including but not limited to text, graphics, logos, images, and software, on our website is the property of our company or our respective partners and is protected by applicable intellectual property laws. You may not reproduce, distribute, modify, or create derivative works from any part of our website without our prior written consent.</p>

	<h2>7. Limitation of Liability</h2>
	<p>In no event shall our company, its affiliates, or its suppliers be liable for any indirect, incidental, special, or consequential damages arising out of or in connection with the use of our website or services, including but not limited to lost profits, lost data, or business interruption, even if advised of the possibility of such damages.</p>

	<h2>8. Governing Law</h2>
	<p>These Terms & Conditions shall be governed by and construed in accordance with the laws of [Your State/Country]. Any disputes arising from or related to these terms shall be subject to the exclusive jurisdiction of the courts located in [Your City/State/Country].</p>

	<h2>9. Changes to Terms & Conditions</h2>
	<p>We reserve the right to modify or update these Terms & Conditions at any time without prior notice. Your continued use of our website after any changes constitutes your acceptance of the new Terms & Conditions.</p>

	<p>If you have any questions or concerns regarding these Terms & Conditions, please contact our customer service team.</p>

	<a href="homepage.php" class="back-to-home">Back to Homepage</a>
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