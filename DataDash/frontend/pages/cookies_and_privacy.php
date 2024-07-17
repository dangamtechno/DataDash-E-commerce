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
	<title>Cookies & Privacy Policy</title>
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
		ul {
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
	<h2>Cookies & Privacy Policy</h2>

	<h2>Cookies</h2>
	<p>Our website uses cookies to enhance your browsing experience and provide personalized services. Cookies are small text files that are stored on your device when you visit our website. They help us understand how you interact with our site, remember your preferences, and improve the overall user experience.</p>

	<p>We use the following types of cookies:</p>
	<ul>
		<li><strong>Essential Cookies:</strong> These cookies are necessary for the proper functioning of our website and cannot be disabled. They enable core functionalities such as secure login, shopping cart, and checkout processes.</li>
		<li><strong>Analytical Cookies:</strong> These cookies collect information about how you use our website, such as the pages you visit and the links you click. This data helps us analyze website traffic and improve our services.</li>
		<li><strong>Advertising Cookies:</strong> These cookies are used to deliver relevant advertisements to you based on your interests and browsing behavior. They may also be used to track the effectiveness of our advertising campaigns.</li>
	</ul>

	<p>You can manage your cookie preferences through your browser settings. However, please note that disabling certain cookies may affect the functionality of our website.</p>

	<h2>Privacy</h2>
	<p>We respect your privacy and are committed to protecting your personal information. We collect and use your data only for the purposes outlined in our Privacy Policy, which includes:</p>
	<ul>
		<li>Processing and fulfilling your orders</li>
		<li>Providing customer support and improving our services</li>
		<li>Sending you promotional offers and updates (if you have opted-in)</li>
		<li>Complying with legal obligations</li>
	</ul>

	<p>We implement appropriate security measures to safeguard your personal data from unauthorized access, disclosure, or misuse. We do not sell or share your information with third parties for marketing purposes without your explicit consent.</p>

	<p>For more detailed information about our data collection practices, please refer to our Cookies & Privacy Page.</p>

	<p>If you have any questions or concerns regarding our Cookies & Privacy Policy, please don't hesitate to contact our customer service team.</p>

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