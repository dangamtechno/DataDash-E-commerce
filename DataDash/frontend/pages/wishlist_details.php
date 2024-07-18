<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!sessionExists()) {
    echo "<p class='error-message'>You must be logged in to view your wishlist.</p>";
    echo "<a href='login_page.php'>Login</a>";
    exit;
}

// Get user ID from the session
$user_id = getSessionUserId();

// Get the wishlist ID from the URL
$wishlist_id = isset($_GET['wishlist_id']) ? intval($_GET['wishlist_id']) : 0;

// Fetch wishlist name
$stmt = $conn->prepare("SELECT wishlist_name FROM wishlist WHERE user_id = ? AND wishlist_id = ?");
$stmt->bind_param("ii", $user_id, $wishlist_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist = $result->fetch_assoc();
$wishlist_name = $wishlist['wishlist_name'];

// Fetch wishlist items from the database
$wishlist_items = $conn->prepare("
    SELECT 
        p.product_id, 
        p.image, 
        p.name, 
        p.price
    FROM 
        product p
    JOIN 
        wishlist_products wp ON p.product_id = wp.product_id
    WHERE 
        wp.wishlist_id = ? AND wp.user_id = ?
");
$wishlist_items->bind_param("ii", $wishlist_id, $user_id);
$wishlist_items->execute();
$result = $wishlist_items->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5,minimum-scale=1.0">
    <script src="https://kit.fontawesome.com/d0ce752c6a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../css/style.css">
    <title>Wishlist Details</title>
    
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
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

        .wishlist-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .wishlist-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin-left: 0px;
        }

        .wishlist-item {
            width: calc(23% - 20px); 
            margin-bottom: 20px;
            margin-right: 20px;
            padding: 20px;
            box-sizing: border-box;            
        }


        .wishlist-item img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .wishlist-item a {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .wishlist-item a:hover {
            text-decoration: underline;
        }
        

        .add-to-cart, .delete-from-wishlist {
            display: block;
            width: 100%;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .add-to-cart {
            background-color: #0ad4f8;
            color: #fff;
            border-radius: 30px;
        }

        .add-to-cart:hover {
            background-color: #07eaff;
        }

        .delete-from-wishlist {
            background-color: #e10000;
            color: #fff;
            border-radius: 30px;
        }

        .delete-from-wishlist:hover {
            background-color: #f00;
        }

        .delete-from-wishlist img {
            width: 20px;
            height: 20px;
        }

        h2 a {
            text-decoration: none; 
            color: black; 
        }

        h2 a:hover {
            text-decoration: underline; 
            color: black; 
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
<section class="wishlist-container">
<div>
    <h2><a href="wishlists.php">Wishlists</a></h2>
</div>
    <h2><?php echo htmlspecialchars($wishlist_name); ?></h2>

    <div class="wishlist-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="wishlist-item">
                    <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                        <img src="../images/electronic_products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                    </a>
                    <form action="../../backend/utils/add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" class="add-to-cart">Add to Cart</button>
                    </form>
                    <form action="../../backend/utils/delete_from_wishlist.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="wishlist_id" value="<?php echo $wishlist_id; ?>">
                        <button type="submit" class="delete-from-wishlist">
                            <img src="../images/bin.png" alt="Delete">
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
</section>
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
