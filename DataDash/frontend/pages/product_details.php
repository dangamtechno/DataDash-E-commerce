<?php
require_once '../../backend/utils/session.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Retrieve the product ID from the query parameter
$product_id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if the product ID is provided and valid
if ($product_id === null) {
    header('Location: shop.php'); // Redirect to shop page if product ID is not provided
    exit;
}

// Query the database to fetch product details, inventory, and category/brand information
$product = $conn->query("SELECT p.product_id, p.category_id, p.brand_id, p.name, p.description, p.price, p.image,
                    p.status, p.date_added, i.quantity AS inventory, c.category_name, b.brand_name FROM product p
                    LEFT JOIN inventory i ON p.product_id = i.product_id LEFT JOIN category c ON 
                    p.category_id = c.category_id LEFT JOIN brands b ON p.brand_id = b.brand_id WHERE 
                    p.product_id = '$product_id'");

// Check if the product exists
if ($product->num_rows > 0) {
    $product_data = $product->fetch_assoc();
} else {
    header('Location: shop.php'); // Redirect to shop page if product not found
    exit;
}
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
    <?php require_once '../../backend/utils/session.php'; ?>
    <title>Product Details - DataDash</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .product-details {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 400px;
            height: 400px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product-information {
            margin-left: 20px;
            padding: 20px;
            border-left: 1px solid #ddd;
        }

        .product-information h2 {
            margin-top: 0;
        }

        .product-information ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .product-information li {
            margin-bottom: 10px;
        }

        .product-information li:last-child {
            margin-bottom: 0;
        }

        .add-to-cart {
            background-color: #0ad4f8;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .add-to-wishlist {
            background-color: #d3c227;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .shop-button {
            display: inline-block;
            padding: 15px 15px; /* Reduced padding for smaller size */
            font-size: 14px; /* Smaller font size */
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

        .buy-now {
            background-color: #aa00ff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 121px;
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
        </div>
        <div class="shop-button-container">
            <a href="shop.php" class="shop-button">Continue Shopping</a>
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
    <section class="product-details">
        <div class="product-image">
            <img src="../images/electronic_products/<?= $product_data['image'] ?>" alt="<?= $product_data['name'] ?>" width="400" height="400">
        </div>
        <div class="product-information">
            <h2><?= $product_data['name'] ?></h2>
            <?php if (isset($_GET['added']) && $_GET['added'] == 'true'): ?>
            <p style="color: green; text-align: center; margin-top: 20px; font-size: 24px;"><?= $product_data['name'] ?> has been added to your cart!</p>            <?php endif; ?>
            <ul>
                <li>Price: $<?= $product_data['price'] ?></li>
                <li>Description: <?= $product_data['description'] ?></li>
                <li>Category: <?= $product_data['category_name'] ?></li>
                <li>Brand: <?= $product_data['brand_name'] ?></li>
                <li>Available Quantity: <?= $product_data['inventory'] ?></li>
            </ul>
            <form action="../../backend/utils/add_to_cart.php" method="post">
              <label for="quantity">Quantity:</label>
              <input type="number" id="quantity" name="quantity" min="1" max="<?= $product_data['inventory'] ?>" value="1">
              <input type="hidden" name="product_id" value="<?= $product_data['product_id'] ?>">
              <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
            <br>


            <form id="form1" <form action="../../backend/utils/add_to_cart.php" method="post">
              <label for="quantity">Quantity:</label>
              <input type="hidden" name="product_id" value="<?= $product_data['product_id'] ?>">
                <input type="number" id="quantity" name="quantity" min="1" max="<?= $product_data['inventory'] ?>" value="1">
            </form>

            <form id="form2" <form action="checkout.php" method="post">
             <input type="hidden" name="action" value="checkout">
             <input type="hidden" name="selected_products" id="selected-products">
             <input type="hidden" name="selected_quantities" id="selected-quantities">
             <button type="submit" class="buy-now" onclick="submitForm('form1', 'form2')">Buy Now</button>
            </form>


            <br>
            <form action="../../backend/utils/add_to_wishlist.php" method="post">
                <input type="hidden" name="product_id" value="<?= $product_data['product_id'] ?>">
                <button type="submit" class="add-to-wishlist">Add to wishlist</button>
            </form>
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
<script src="../js/navbar.js"></script>
<script src="../js/search.js"></script>
<script>
  function submitForm(formId) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(form.action, {
      method: form.method,
      body: formData
    })
    .then(response => {
      // Handle response from server
    })
    .catch(error => {
      // Handle error
    });
  }
</script>
</body>
</html>