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

	<title>Returns Policy</title>

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
		.highlight {
			background-color: #f5f5f5;
			padding: 10px;
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
	<h1>Returns Policy</h1>

	<p>At our e-commerce website, we strive to provide you with high-quality products and excellent customer service. If for any reason you are not satisfied with your purchase, we offer a hassle-free return policy.</p>

	<h2>Return Eligibility</h2>
	<p>You can return most items within 30 days of delivery for a full refund or exchange, provided that the items are in their original condition, unworn, and unwashed. Here are the general guidelines for our return policy:</p>
	<ul>
		<li>Items must be returned within 30 days of delivery.</li>
		<li>Items must be in their original condition, unworn, and unwashed.</li>
		<li>Items must have all original tags and labels attached.</li>
		<li>Items must be returned with their original packaging and accessories.</li>
	</ul>

	<h2>How to Return an Item</h2>
	<p>To initiate a return, please follow these steps:</p>
	<ol>
		<li>Visit our Returns page and complete the online return form.</li>
		<li>You'll receive a return authorization number and instructions on how to ship the item back to us.</li>
		<li>Pack the item securely in its original packaging, including all accessories and tags.</li>
		<li>Ship the item back to us using a trackable shipping method.</li>
		<li>Once we receive and process your return, we'll issue a refund or send you a replacement item, depending on your preference.</li>
	</ol>

	<h2>Refunds and Exchanges</h2>
	<p>If you choose to return an item for a refund, we'll credit the original payment method used for your purchase once we receive and process the returned item. Please note that it may take up to 5-7 business days for the refund to reflect in your account.</p>

	<p>If you prefer an exchange, we'll gladly send you a replacement item once we receive and process your return. Any additional charges or credits will be applied accordingly.</p>

	<div class="highlight">
		<h2>Important Note</h2>
		<p>Please note that certain items may be subject to different return policies or restrictions due to their nature or manufacturer guidelines. These exceptions will be clearly stated on the respective product pages. If you have any questions or concerns, please don't hesitate to contact our customer service team.</p>
	</div>

	<p>We hope this returns policy provides you with the information you need. If you have any further questions or require assistance, please feel free to reach out to our customer service team.</p>
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
