<?php
require_once '../../backend/utils/session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!sessionExists()) {
    echo "<p class='error-message'>You must be logged in to reset your password.</p>";
    echo "<a href='login_page.php'>Login</a>";
    exit;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wishlist</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5,minimum-scale=1.0">
    <script src="https://kit.fontawesome.com/d0ce752c6a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; 
          require_once '../../backend/include/database_config.php';?>
    
</head>
<style>
        .shop-button-container {
            text-align: center;
            margin-top: 10px;
        }

        .shop-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #1e1f22;
            background-color: #009dff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .shop-button:hover {
            background-color: #0056b3;
        }
        .wishlist-container {
            max-width: 1500px;
            margin: 100px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .wishlist {
            width: 24%;
            margin-bottom: 20px;
        }

        .wishlist-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin-left: 50px;
        }

        .wishlist img {
            max-width: 100%;
            height: auto;
            width: 275px;
            height: 275px;
            object-fit: contain;
        }

        .wishlist a {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .wishlist a:hover {
            text-decoration: underline;
        }

        .shop-button {
            display: inline-block;
            padding: 10px 20px;
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

        .add-to-cart {
            background-color: #0ad4f8;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .add-to-cart:hover {
            background-color: #07eaff;
        }

        .delete-from-wishlist {
            background-color: #e10000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin: 20px;
            border-radius: 5px;
        }
        .delete-from-wishlist:hover {
            background-color: #f00;
        }

        .delete-from-wishlist img {
            width: 20px;
            height: 10px;
        }

    </style>
<body>
<header> <div class="heading">
            <div class="left-heading">
                <div class="logo">
                    <a href="homepage.php">
                        <img src="../images/misc/DataDash.png" alt="Logo" width="105" height="500">
                    </a>
                </div>
                <div class="search-bar">
                    <form class="search-form">
                        <label>
                            <input type="search" name="search" placeholder="search...">
                        </label>
                        <input type="submit" name="submit-search" class ="search-button">
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
    <h1>Wishlist</h1>
    
    <section class="wishlist-container">
        <div class="wishlist-grid" id="wishlist-grid">
                <?php

                
                $conn = new mysqli("localhost", "root", "", "datadash");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                
                $products = $conn->query("SELECT product.product_id, product.image, product.name, product.price
                FROM product
                INNER JOIN wishlist_products ON product.product_id = wishlist_products.product_id");
                if ($products->num_rows == 0) {  // Check if there are no products
                    echo '<div class="wishlist-container">';
                    echo '<p> Your wishlist is empty </p>';
                    echo '</div>';
                }else{
                    echo '<div> 
                            <h1> Wishlist </h1>
                        </div>';

                    foreach ($products as $product) {
                        echo '<div class="wishlist">';
                        echo '<a href="product_details.php?id=' . $product['product_id'] . '">';
                        echo '<img src="../images/electronic_products/' . $product['image'] . '" alt="' . $product['name'] . '">';
                        echo '<h3>' . $product['name'] . '</h3>';
                        echo '<p>$' . $product['price'] . '</p>';
                        echo '</a>';
                        echo '<form action="../../backend/utils/add_to_cart.php" method="post">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" min="1" max="' . $product['inventory'] . '" value="1">
                            <input type="hidden" name="product_id" value="' . $product['product_id'] . '">
                            <button type="submit" class="add-to-cart">Add to Cart</button>
                        </form>';
                        echo '</form>
                                <form action="../../backend/utils/delete_from_wishlist.php" method="post">
                                <input type="hidden" name="product_id" value="' . $product['product_id'] . '">
                                <button type="submit" class="delete-from-wishlist">
                                <img src="../images/bin.png" alt="Delete"></button>
                        </form>';
                        echo '</div>';    
                    }
                }
                $conn->close();
                ?>
            </div>
    </section>
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
    <script src="../js/wishlist.js"></script>
</body>
</html>