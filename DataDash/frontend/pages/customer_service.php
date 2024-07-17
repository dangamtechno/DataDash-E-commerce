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
	<title>Customer Service</title>
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
		h1, h2 {
			color: #333;
			margin-top: 30px;
		}
		p {
			line-height: 1.5;
			margin-bottom: 10px;
		}
		ul {
			list-style-type: none;
			padding: 0;
		}
		li {
			margin-bottom: 10px;
		}
		.contact-info {
			background-color: #f5f5f5;
			padding: 20px;
			border-radius: 5px;
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
        a.email-link {
			color: blue;
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
	<h1>Customer Service</h1>

	<p>At our e-commerce website, we are committed to providing exceptional customer service. If you have any questions, concerns, or need assistance with your order, our friendly and knowledgeable customer service team is here to help.</p>

	<h2>Contact Us</h2>
	<div class="contact-info">
		<h3>Email</h3>
		<p>For general inquiries or order-related questions, please email us at <a href="mailto:support@example.com" class="email-link">support@example.com</a>.</p>

		<h3>Phone</h3>
		<p>You can reach our customer service representatives by calling 1-800-123-4567. Our phone lines are open Monday to Friday, from 9 AM to 5 PM EST.</p>

		<h3>Live Chat</h3>
		<p>If you prefer real-time assistance, you can chat with one of our customer service agents by clicking the chat icon on our website during business hours.</p>

		<h3>Mailing Address</h3>
		<p>For any written correspondence or returns, please use the following mailing address:</p>
		<ul>
			<li>Customer Service Department</li>
			<li>123 Main Street</li>
			<li>City, State 12345</li>
			<li>Country</li>
		</ul>
	</div>

	<p>Our customer service team is dedicated to addressing your concerns promptly and providing you with the best possible experience. We strive to respond to all inquiries within 24 hours during business days.</p>

	<p>If you have any other questions or need further assistance, please don't hesitate to reach out to us through any of the channels listed above.</p>

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
                2024 DataDash, All Rights Reserved.
        </div>
    </div>
</footer>
<script src="../js/search.js"></script>
</body>
</html>

