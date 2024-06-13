<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wishlist</title>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>
</head>
<style>
        .wishlist-container {
             max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .wishlist-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-left: -10px; /* Adjust to balance the padding */
            margin-right: -10px; /* Adjust to balance the padding */
        }

        .wishlist {
            width: 30%;
            margin-bottom: 20px;
            justify-content: center;
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
        }
        .delete-from-wishlist:hover {
            background-color: #f00;
        }
    </style>
<body>
    <h1>Wishlist</h1>
    <div class="topnav">
        <a href="homepage.php">Home</a>
        <?php if (sessionExists()): ?>
            <a href="cart.php">Shopping Cart</a>
            <a href="wishlist.php">Wishlist</a>
        <?php endif; ?>
        <?php if (sessionExists()): ?>
            <a href="../../backend/utils/logout.php">Logout</a>
        <?php else: ?>
            <a href="login_page.php">Login</a>
        <?php endif; ?>
    </div>
    <div class="wishlist-container">
        <table class="wishlist-table">
        <div class="wishlist-grid">
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
                    foreach ($products as $product) {
                        echo '<div class="wishlist">';
                        echo '<a href="product_details.php?id=' . $product['product_id'] . '">';
                        echo '<img src="../images/' . $product['image'] . '" alt="' . $product['name'] . '">';                       
                        echo '<h3>' . $product['name'] . '</h3>';
                        echo '<p>$' . $product['price'] . '</p>';
                        echo '</a>';
                        echo '<button type="submit" class="add-to-cart">Add to Cart</button>';
                        echo '<button type="submit" class="delete-from-wishlist">Delete</button>';
                        echo '</div>';
                    }
                }
                $conn->close();
                ?>
            </div>
        </table>
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
    2024 DataDash, All Rights Reserved.
</footer>
    <script src="../js/wishlist.js"></script>
</body>
</html>