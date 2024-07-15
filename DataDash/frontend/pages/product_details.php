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
                    p.status, p.date_added, i.quantity AS inventory, c.category_name, b.brand_name, 
                    AVG(r.rating) AS average_rating
                    FROM product p
                    LEFT JOIN inventory i ON p.product_id = i.product_id 
                    LEFT JOIN category c ON p.category_id = c.category_id 
                    LEFT JOIN brands b ON p.brand_id = b.brand_id
                    LEFT JOIN reviews r ON p.product_id = r.product_id
                    WHERE p.product_id = '$product_id'
                    GROUP BY p.product_id");


// Check if the product exists
if ($product->num_rows > 0) {
    $product_data = $product->fetch_assoc();
} else {
    header('Location: shop.php'); // Redirect to shop page if product not found
    exit;
}

// Fetch wishlists for the current user
$wishlists = [];
if (sessionExists()) {
    $user_id = getSessionUserId();
    $stmt = $conn->prepare("SELECT w.wishlist_id, w.wishlist_name FROM wishlist w WHERE w.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $wishlists[] = $row;
    }
}

// Check if user has already reviewed this product
$existing_review = false;
$user_rating = null; // Variable to store the user's existing rating
if (sessionExists()) {
    $user_id = getSessionUserId();
    $existing_review_query = "SELECT * FROM reviews WHERE user_id = $user_id AND product_id = $product_id";
    $existing_review_result = $conn->query($existing_review_query);
    if ($existing_review_result->num_rows > 0) {
        $existing_review = true;
        $user_review_data = $existing_review_result->fetch_assoc();
        $user_rating = $user_review_data['rating']; // Get the user's existing rating
    }
}

// Fetch reviews for this product
$reviews_query = "SELECT reviews.*, users.first_name, users.last_name FROM reviews 
                  JOIN users ON reviews.user_id = users.user_id 
                  WHERE reviews.product_id = $product_id";
$reviews = $conn->query($reviews_query);

$conn->close(); // Close the connection

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
            background-color: #dac81d;
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

        .custom-dropdown {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .custom-dropdown select {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: #fff url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTEiIGhlaWdodD0iNiIgdmlld0JveD0iMCAwIDExIDYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTEuNzkyNDQsMEw1LjUwMDIzLDIuNTE1MjFMMTAuMTIwMSwwTDExLDAuNzA4NzA4TDUsNi4wNzU4NUwwLjAwMDAyMzI0LDAuNzA4NzA4TDEuNzkyNDQsMFoiIGZpbGw9IiM2NjYiLz48L3N2Zz4=') no-repeat right 10px center;
            background-size: 10px 5px;
        }

        .custom-dropdown select:focus {
            outline: none;
            border-color: #009dff;
        }

        .review-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .review-container h2 {
            margin-bottom: 10px;
        }

        .review-form {
            display: flex;
            flex-direction: column;
        }

        .review-form label {
            margin-bottom: 5px;
        }

        .review-form textarea {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        .review-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            /* Disable the button by default */
            opacity: 0.5;
            pointer-events: none;
        }

        .review-form input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        .review-form input[type="submit"]:enabled {
            /* Enable the button when valid */
            opacity: 1;
            pointer-events: auto;
        }

        .past-reviews-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .past-reviews-container h2 {
            margin-bottom: 10px;
        }

        .past-review {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
        }

        .past-review .user-info {
            margin-bottom: 10px;
        }

        .past-review .review-text {
            margin-bottom: 5px;
        }

        .rating-container {
            display: flex;
            align-items: center;
        }

        .star {
            font-size: 2em;
            color: #ddd;
            margin-right: 5px;
            cursor: pointer;
        }

        .star.one {
            color: rgb(212, 175, 55);
        }

        .star.two {
            color: rgb(212, 175, 55);
        }

        .star.three {
            color: rgb(212, 175, 55);
        }

        .star.four {
            color: rgb(212, 175, 55);
        }

        .star.five {
            color: rgb(212, 175, 55);
        }

        .disabled-star {
            cursor: default; /* Remove pointer cursor */
            pointer-events: none; /* Disable interaction */
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
                <li>Rating:
                    <?php
                    if (!is_null($product_data['average_rating'])) {
                        echo $product_data['average_rating'];
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </li>
            </ul>
            <br>

            <form action="../../backend/utils/add_to_cart.php" method="post">
              <label for="quantity">Quantity:</label>
              <input type="number" id="quantity" name="quantity" min="1" max="<?= $product_data['inventory'] ?>" value="1">
              <input type="hidden" name="product_id" value="<?= $product_data['product_id'] ?>"> <br>
              <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
            <br>


            <form id="form1" <form action="../../backend/utils/add_to_cart.php" method="post">
              <label for="quantity">Quantity:</label>
              <input type="number" id="quantity" name="quantity" min="1" max="<?= $product_data['inventory'] ?>" value="1">
              <input type="hidden" name="product_id" value="<?= $product_data['product_id'] ?>">
            </form>

            <form id="form2" <form action="checkout.php" method="post" id="buy-now-form">
                <input type="hidden" name="action" value="checkout">
                <input type="hidden" name="selected_products" value='[<?= $product_data['product_id'] ?>]'>
                <input type="hidden" name="selected_quantities" id="selected-quantities">
                <button type="submit" class="buy-now" onclick="submitForms('form1', 'form2')">Buy Now</button>
            </form>


            <br><br>

            <form action="../../backend/utils/add_to_wishlist.php" method="post" class="custom-dropdown">
                <label for="wishlist">Add to Wishlist:</label>
                <select id="wishlist" name="wishlist_id">
                    <option value="select">Select</option>
                    <?php foreach ($wishlists as $wishlist): ?>
                        <option value="<?= htmlspecialchars($wishlist['wishlist_id']) ?>"><?= htmlspecialchars($wishlist['wishlist_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_data['product_id']) ?>">
                <button type="submit" class="add-to-wishlist">Add to Wishlist</button>
            </form>
        </div>
    </section>
    <div class="review-container">
        <h2>Leave a Review!</h2>
        <?php function getStarColorClass(int $i)
        {

        }

        if (sessionExists() && !$existing_review): ?>
            <form class="review-form" action="../../backend/models/reviews.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <label for="review-text">Your Review:</label>
                <textarea id="review-text" name="review_text" placeholder="Write your review here..."></textarea>
                <div class="rating-container">
                    <label for="rating">Rating:</label>
                    <span class="star" data-value="1">★</span>
                    <span class="star" data-value="2">★</span>
                    <span class="star" data-value="3">★</span>
                    <span class="star" data-value="4">★</span>
                    <span class="star" data-value="5">★</span>
                    <input type="hidden" name="rating" id="rating" value="">
                </div>
                <input type="submit" value="Submit Review" disabled>
            </form>
        <?php elseif (sessionExists() && $existing_review): ?>
            <p>You have already left a review for this product.</p>
            <div class="rating-container">
                <?php
                // Display the user's existing rating with disabled stars
                for ($i = 1; $i <= 5; $i++) {
                    $starClass = ($i <= $user_rating) ? 'star ' . getStarColorClass($i) : 'star disabled-star';
                    echo '<span class="' . $starClass . '" data-value="' . $i . '">★</span>';
                }
                ?>
            </div>
        <?php else: ?>

            <p>Please log in to leave a review.</p>
        <?php endif; ?>
    </div>

    <div class="past-reviews-container">
        <h2>Past Reviews</h2>
        <?php
        // Display the reviews
        if ($reviews->num_rows > 0) {
            while ($review = $reviews->fetch_assoc()) {
                echo '<div class="past-review">';
                echo '<div class="user-info">';
                echo '<p><strong>' . $review['first_name'] . ' ' . $review['last_name'] . '</strong></p>';
                echo '<p>' . date("j M Y", strtotime($review['review_date'])) . '</p>';
                echo '</div>';
                echo '<div class="rating-container">';
                for ($i = 1; $i <= 5; $i++) {
                    $starClass = ($i <= $review['rating']) ? 'star ' . getStarColorClass($i) : 'star';
                    echo '<span class="' . $starClass . '" data-value="' . $i . '">★</span>';
                }
                echo '</div>';
                echo '<p class="review-text">' . $review['review_text'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No reviews yet. Be the first to leave a review!</p>';
        }
        ?>
    </div>
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
<script src="../js/buy_now.js"></script>
<script>
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const submitButton = document.querySelector('.review-form input[type="submit"]');
    const reviewText = document.getElementById('review-text');

    // If user has already reviewed, disable stars and make them reflect their rating
    <?php if (sessionExists() && $existing_review): ?>
        stars.forEach(star => {
            star.classList.add('disabled-star');
            star.removeEventListener('click', setStarRating); // Remove click listener
        });
        setStarRating(stars, <?php echo $user_rating; ?>); // Set initial rating based on existing review
    <?php endif; ?>

    stars.forEach(star => {
        star.addEventListener('click', (event) => {
            let rating = parseInt(event.target.dataset.value);
            setStarRating(stars, rating);
            ratingInput.value = rating;
            checkSubmitButton();
        });
    });

    function setStarRating(stars, rating) {
        stars.forEach(star => {
            star.classList.remove('one', 'two', 'three', 'four', 'five'); // Reset all stars
            if (parseInt(star.dataset.value) <= rating) {
                // Apply gold color class based on rating
                star.classList.add(getStarColorClass(star.dataset.value));
            }
        });
    }

    // Helper function to map rating to color class
    function getStarColorClass(rating) {
        switch (parseInt(rating)) {
            case 1: return 'one';
            case 2: return 'two';
            case 3: return 'three';
            case 4: return 'four';
            case 5: return 'five';
            default: return '';
        }
    }

    function checkSubmitButton() {
        if (ratingInput.value !== '' && reviewText.value.trim() !== '') {
            submitButton.disabled = false;
            submitButton.style.opacity = 1;
            submitButton.style.pointerEvents = 'auto';
        } else {
            submitButton.disabled = true;
            submitButton.style.opacity = 0.5;
            submitButton.style.pointerEvents = 'none';
        }
    }

    reviewText.addEventListener('input', checkSubmitButton);
</script>
</body>
</html>