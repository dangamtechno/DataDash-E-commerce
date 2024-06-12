<?php

require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkout') {
    // 1. Get selected product IDs and quantities from the POST data
    $userId = getSessionUserId();
    $selectedProductIds = isset($_POST['selected_products']) ? $_POST['selected_products'] : [];
    $selectedQuantities = isset($_POST['selected_quantities']) ? $_POST['selected_quantities'] : [];

    // 2. Retrieve product information for selected products
    $selectedProducts = [];
    if (!empty($selectedProductIds)) {
        for ($i = 0; $i < count($selectedProductIds); $i++) {
            $productId = $selectedProductIds[$i];
            $quantity = isset($selectedQuantities[$productId]) ? $selectedQuantities[$productId] : 1;

            $sql = "SELECT product_id, name, price, image FROM product WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $product = $result->fetch_assoc();
                $product['quantity'] = $quantity;
                $selectedProducts[] = $product;
            }
        }
    }

    // 3. Calculate the total price (including any discounts, taxes, shipping)
    // (Add your logic for discounts, taxes, shipping here)
    $totalPrice = 0;
    foreach ($selectedProducts as $product) {
        $totalPrice += $product['price'] * $product['quantity'];
    }

    // 4. Process payment (e.g., using a payment gateway)
    // (Add your payment processing logic here, replace with your actual payment gateway integration)
    // For this example, we'll just simulate a successful payment.
    $paymentSuccessful = true;

    // 5. Create an order in the database (with order details)
    if ($paymentSuccessful) {
        // Create an order
        $sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES (?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $userId, $totalPrice);
        if ($stmt->execute()) {
            $orderId = $conn->insert_id;
            echo "Order created successfully! Order ID: " . $orderId;

            // Add order details to order_details table
            foreach ($selectedProducts as $product) {
                $sql = "INSERT INTO order_details (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiid", $orderId, $product['product_id'], $product['quantity'], $product['price']);
                $stmt->execute();
            }

            // 6. Update inventory if necessary
            foreach ($selectedProducts as $product) {
                $sql = "UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $product['quantity'], $product['product_id']);
                $stmt->execute();
            }

            // 7. Redirect to a confirmation page or display a success message
            header('Location: ../../frontend/pages/checkout_confirmation.php?order_id=' . $orderId);
            exit;
        } else {
            echo "Error creating order: " . $stmt->error;
        }
    } else {
        echo "Payment failed. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Checkout</title>
    <style>
        /* ... (Add any necessary styles here) ... */
    </style>
</head>
<body>
    <header>
        <div class="heading">
            <div class="left-heading">
                <div class="logo">
                    <a href="homepage.php">
                        <img src="../images/DataDash.png" alt="Logo" width="85" height="500">
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
    <main>
        <div class="checkout-container">
            <h2>Checkout</h2>
            <?php
            if (sessionExists() && !empty($_GET['products'])) {
                $userId = getSessionUserId();
                $selectedProductIds = explode(',', $_GET['products']);
                $selectedProducts = [];

                if (!empty($selectedProductIds)) {
                    $sql = "SELECT product_id, name, price, image FROM product WHERE product_id IN (" . implode(',', $selectedProductIds) . ")";
                    $result = $conn->query($sql);
                    if ($result) {
                        $selectedProducts = $result->fetch_all(MYSQLI_ASSOC);
                    }
                }

                if (!empty($selectedProducts)) {
                    ?>
                    <h3>Order Summary</h3>
                    <table class="checkout-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalPrice = 0;
                            foreach ($selectedProducts as $product) {
                                $totalPrice += $product['price'];
                                ?>
                                <tr>
                                    <td>
                                        <img src="../images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                                        <?php echo $product['name']; ?>
                                    </td>
                                    <td>
                                        <input type="number" name="selected_quantities[<?php echo $product['product_id']; ?>]" value="1" min="1" required>
                                    </td>
                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="checkout-total">
                        <h3>Total: $<?php echo number_format($totalPrice, 2); ?></h3>
                    </div>

                    <h3>Shipping Details</h3>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="action" value="checkout">
                        <input type="hidden" name="selected_products" value="<?php echo implode(',', $selectedProductIds); ?>">

                        <!-- Add your shipping address form fields here -->
                        <!-- Example: -->
                        <label for="shipping-address">Shipping Address:</label><br>
                        <input type="text" id="shipping-address" name="shipping-address" required><br><br>

                        <h3>Payment Information</h3>
                        <!-- Add your payment information form fields here -->
                        <!-- Example: -->
                        <label for="card-number">Card Number:</label><br>
                        <input type="text" id="card-number" name="card-number" required><br><br>

                        <!-- ... Other payment form fields ... -->

                        <button type="submit" class="checkout-button">Place Order</button>
                    </form>

                    <?php
                } else {
                    echo '<p>No products selected for checkout.</p>';
                }
            } else {
                echo '<p>You must select products to checkout.</p>';
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
            </ul>
        </div>
    </div>
    2024 DataDash, All Rights Reserved.
</footer>
    <script src="../js/navbar.js"></script>
    <script src="../js/slider.js"></script>
</body>
</html>