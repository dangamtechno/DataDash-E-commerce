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
	<title>Frequently Asked Questions</title>
	<style>

        .shop-button-container {
        text-align: center; /* Center the button horizontally */
        margin-top: 0px;
        border-radius: 30px; /* Rounded corners */
        }

        .shop-button {
            display: inline-block;
            padding: 15px 40px;
            font-size: 16px;
            color: #fff;
            background-color: #009dff; /* Bootstrap primary color */
            border: none;
            border-radius: 30px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-left: 45px; /* Add 45px left margin */
        }

        .shop-button:hover {
            background-color: #0056b3; /* Darker shade for hover effect */
        }

		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
		}
		h2 {
			color: #333;
			margin-top: 30px;
		}
		h3 {
			color: #666;
			margin-top: 20px;
		}
		p {
			line-height: 1.5;
			margin-bottom: 10px;
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

        /* Search Bar Styling */
        .search-bar {
            position: relative; /* To position the search icon */
            width: 400px; /* Adjust width as needed */
            margin: 8px auto; /* Center the search bar */
        }

        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 30px; /* Rounded corners */
            font-size: 16px;
        }

        .search-bar input[type="submit"] {
            position: absolute;
            left: 400px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #7909f1; /* Bootstrap primary color */
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
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
                <div class="shop-button-container">
                <a href="shop.php" class="shop-button">Shop</a>
                </div>
            </div> <br>
            <div class="search-bar">
                <form id="search-form" method="GET" action="shop.php">
                    <label>
                        <input type="search" name="search" id="search-input" placeholder="search...">
                    </label>
                    <input type="submit" value="Search">
                </form>
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

	<h1>Frequently Asked Questions</h1>

	<h2>Ordering and Payments</h2>
	<h3>How do I place an order?</h3>
	<p>To place an order on our website, follow these simple steps:</p>
	<ol>
		<li>Browse our product catalog and add items to your cart.</li>
		<li>Once you're ready to checkout, click on the cart icon and review your order.</li>
		<li>Proceed to the checkout page and enter your shipping and payment information.</li>
		<li>Review your order details and confirm your purchase.</li>
		<li>You'll receive an order confirmation email once your order is processed.</li>
	</ol>

	<h3>What payment methods do you accept?</h3>
	<p>We accept various payment methods for your convenience, including:</p>
	<ul>
		<li>Credit/Debit Cards (Visa, Mastercard, American Express, Discover)</li>
		<li>PayPal</li>
		<li>Apple Pay</li>
		<li>Google Pay</li>
	</ul>

	<h3>Is it safe to provide my credit card information on your website?</h3>
	<p>Yes, your payment information is securely transmitted and processed using industry-standard encryption protocols. We prioritize the safety and security of your personal and financial data.</p>

	<h2>Shipping and Delivery</h2>
	<h3>How much does shipping cost?</h3>
	<p>Shipping costs are calculated based on the weight and destination of your order. During the checkout process, you'll be able to view the shipping options and associated costs before finalizing your purchase.</p>

	<h3>How long will it take to receive my order?</h3>
	<p>Delivery times may vary depending on your location and the shipping method you choose. Standard shipping typically takes 5-7 business days within the continental United States. Expedited shipping options are also available for faster delivery.</p>

	<h3>Can I track my order?</h3>
	<p>Absolutely! Once your order has been shipped, you'll receive a tracking number via email. You can use this tracking number to monitor the progress of your shipment on the carrier's website.</p>
	<a href="homepage.php" style="border-radius: 30px; background-color: #7909f1;" class="back-to-home">Back to Homepage</a>
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
            <img src="../images/misc/DataDash.png" alt="Logo" style="border-radius: 50%;" width="210" height="110">
        </div>
        <div class="legal">
            <h3>Privacy & Legal</h3>
            <ul>
                <li><a href="cookies_and_privacy.php">Cookies & Privacy</a></li>
                <li><a href="terms_and_conditions.php">Terms & Conditions</a></li>
            </ul> <br>
                <h3><strong>2024 DataDash, All Rights Reserved.</strong><h3>
            <ul>
                <h3>admin login</h3>
                <li>
                    <a href="../../admin/frontend/index.html">
                        <i class="fas fa-users-cog"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>
<script src="../js/search.js"></script>
</body>
</html>

