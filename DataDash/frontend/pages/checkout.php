<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve cart items for a given user ID
function getCartItems($userId) {
    global $conn;

    $sql = "SELECT cp.product_id, p.name, p.price, p.image, cp.quantity
            FROM cart_product cp
            JOIN product p ON cp.product_id = p.product_id
            WHERE cp.cart_id IN (SELECT cart_id FROM cart WHERE user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
    }
    return $cartItems;
}

// Function to remove an item from the cart
function removeFromCart($userId, $productId) {
    global $conn;

    $sql = "DELETE FROM cart_product WHERE cart_id IN (SELECT cart_id FROM cart WHERE user_id = ?) AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
}

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkout') {
    // 1. Get selected product IDs and quantities from the POST data
    $userId = getSessionUserId();
    $selectedProductIds = isset($_POST['selected_products']) ? json_decode($_POST['selected_products'], true) : [];
    $selectedQuantities = isset($_POST['selected_quantities']) ? json_decode($_POST['selected_quantities'], true) : [];

    // 2. Retrieve product information for selected products
    $selectedProducts = [];
    $totalPrice = 0;
    if (!empty($selectedProductIds)) {
        foreach ($selectedProductIds as $productId) {
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
                $totalPrice += $product['price'] * $quantity; // Calculate total price here
            }
        }
    }

    // 3. Process payment (e.g., using a payment gateway)
    // (Add your payment processing logic here, replace with your actual payment gateway integration)
    // For this example, we'll just simulate a successful payment.
    $paymentSuccessful = true; // Replace with your payment gateway integration

    // 4. Create an order in the database (with order details)
    if ($paymentSuccessful) {
        $conn->begin_transaction(); // Start a transaction

        try {
            $sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES (?, NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("id", $userId, $totalPrice);
            if ($stmt->execute()) {
                $orderId = $conn->insert_id;

                // Add order details to order_details table
                foreach ($selectedProducts as $product) {
                    $sql = "INSERT INTO order_details (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iiii", $orderId, $product['product_id'], $product['quantity'], $product['price']);
                    $stmt->execute();
                }

                // 5. Update inventory if necessary
                foreach ($selectedProducts as $product) {
                    $sql = "UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $product['quantity'], $product['product_id']);
                    $stmt->execute();
                }

                // 6. Remove items from cart
                foreach ($selectedProducts as $product) {
                    removeFromCart($userId, $product['product_id']);
                }

                $conn->commit(); // Commit the transaction
                $orderCreated = true;
            } else {
                $conn->rollback();
                echo "Error creating order: " . $stmt->error;
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error during order processing: " . $e->getMessage();
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
        .checkout-table img {
            max-width: 100%;
            height: auto;
            width: 275px;
            height: 275px;
            object-fit: contain;
        }
        .checkout-section {
            margin-bottom: 20px;
        }
        .checkout-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .checkout-progress-step {
            text-align: center;
            flex: 1;
        }
        .checkout-progress-step.active {
            color: #337ab7;
        }
        .checkout-progress-step.completed {
            color: #5cb85c;
        }
        .checkout-progress-step.next {
            color: #d9534f;
        }
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
            if (sessionExists()) {
                $userId = getSessionUserId();
                $cartItems = getCartItems($userId);

                if (!empty($cartItems)) {
                    $selectedProductIds = [];
                    $selectedQuantities = [];
                    if (isset($_POST['selected_products'])) {
                        $selectedProductsArray = json_decode($_POST['selected_products'], true); // Decode to an associative array

                        if (isset($selectedProductsArray) && !empty($selectedProductsArray)) {
                            foreach ($cartItems as $item) {
                                if (in_array($item['product_id'], $selectedProductsArray)) {
                                    $selectedProductIds[] = $item['product_id'];
                                    $selectedQuantities[$item['product_id']] = (isset($_POST['selected_quantities'][$item['product_id']]) && is_numeric($_POST['selected_quantities'][$item['product_id']])) ? (int)$_POST['selected_quantities'][$item['product_id']] : 1;
                                }
                            }
                            ?>
                            <div class="checkout-progress">
                                <div class="checkout-progress-step active">Review Your Order</div>
                                <div class="checkout-progress-step next">Shipping Address</div>
                                <div class="checkout-progress-step next">Payment</div>
                                <div class="checkout-progress-step next">Confirmation</div>
                            </div>

                            <div class="checkout-section">
                                <h3>Your Order</h3>
                                <table class="checkout-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalPrice = 0;
                                        foreach ($selectedProductIds as $productId) {
                                            $product = array_filter($cartItems, function($item) use ($productId) {
                                                return $item['product_id'] === $productId;
                                            });
                                            $product = reset($product);
                                            if ($product) {
                                                $quantity = $selectedQuantities[$productId];
                                                $productTotal = $product['price'] * $quantity;
                                                $totalPrice += $productTotal;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <img src="../images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                                                        <?php echo $product['name']; ?>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="selected_quantities[<?php echo $product['product_id']; ?>]" value="<?php echo $quantity; ?>" min="1" required>
                                                    </td>
                                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                    <td>$<?php echo number_format($productTotal, 2); ?></td>
                                                </tr>
                                            <?php }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Subtotal:</th>
                                            <th>$<?php echo number_format($totalPrice, 2); ?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Shipping:</th>
                                            <th>$0.00 (Free)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Total:</th>
                                            <th id="total-price">$<?php echo number_format($totalPrice, 2); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="checkout-section">
                                <h3>Shipping Address</h3>
                                <form action="checkout.php" method="post">
                                    <input type="hidden" name="action" value="checkout">
                                    <input type="hidden" name="selected_products" value="<?php echo json_encode($selectedProductIds); ?>">
                                    <input type="hidden" name="selected_quantities" value="<?php echo json_encode($selectedQuantities); ?>">

                                    <div class="form-group">
                                        <label for="shipping-address">Full Name:</label>
                                        <input type="text" id="shipping-name" name="shipping-name" placeholder="Enter your full name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping-address">Address:</label>
                                        <input type="text" id="shipping-address" name="shipping-address" placeholder="Enter your street address" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping-city">City:</label>
                                        <input type="text" id="shipping-city" name="shipping-city" placeholder="Enter your city" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping-state">State/Province:</label>
                                        <input type="text" id="shipping-state" name="shipping-state" placeholder="Enter your state/province" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping-zip">Zip Code:</label>
                                        <input type="text" id="shipping-zip" name="shipping-zip" placeholder="Enter your zip code" required>
                                    </div>

                                    <div class="checkout-section" id="payment-section">
                                        <h3>Payment Information</h3>
                                        <div class="form-group">
                                            <label for="card-number">Card Number:</label>
                                            <input type="text" id="card-number" name="card-number" placeholder="Enter your card number" required>
                                        </div>
                                        <!-- Add other payment details (expiry date, CVV, etc.) -->

                                        <button type="submit" class="checkout-button">Place Order</button>
                                    </div>
                                </form>

                                <?php
                                if (isset($orderCreated) && $orderCreated) {
                                    ?>
                                    <div class="checkout-section">
                                        <h3>Order Confirmation</h3>
                                        <p>Thank you for your order!</p>

                                        <div class="order-details">
                                            <p>Order ID: <?php echo $orderId; ?></p>
                                            <p>Total Amount: $<?php echo number_format($totalPrice, 2); ?></p>
                                            <!-- Display other order details like shipping address, payment method, etc. -->
                                        </div>

                                        <p>You will receive an email confirmation with order details shortly.</p>
                                        <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php
                        } else {
                            echo '<p>Invalid product selection.</p>';
                        }
                    } else {
                        echo '<p>Please select products for checkout.</p>';
                    }
                } else {
                    echo '<p>Your cart is empty.</p>';
                }
            } else {
                echo '<p>Please <a href="login_page.php">log in</a> to proceed to checkout.</p>';
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
    <script>
        // Update quantity input listeners
        document.querySelectorAll('.checkout-table input[type="number"]').forEach(input => {
            input.addEventListener('change', function() {
                updateCartTotal();
            });
        });

        function updateCartTotal() {
            let totalPrice = 0;
            document.querySelectorAll('.checkout-table tr').forEach(row => {
                const quantityInput = row.querySelector('input[type="number"]');
                const priceCell = row.querySelector('td:nth-child(3)');
                const totalCell = row.querySelector('td:nth-child(4)');

                if (quantityInput && priceCell && totalCell) {
                    const quantity = parseInt(quantityInput.value, 10);
                    const price = parseFloat(priceCell.textContent.replace('$', ''));
                    const total = quantity * price;
                    totalCell.textContent = '$' + total.toFixed(2);
                    totalPrice += total;
                }
            });

            // Update total price in the footer
            document.getElementById('total-price').textContent = '$' + totalPrice.toFixed(2);
        }

        // Show payment section when shipping details are filled out
        const shippingForm = document.querySelector('form');
        shippingForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            // Check if all required fields are filled out
            if (document.getElementById('shipping-name').value &&
                document.getElementById('shipping-address').value &&
                document.getElementById('shipping-city').value &&
                document.getElementById('shipping-state').value &&
                document.getElementById('shipping-zip').value) {
                document.getElementById('payment-section').style.display = 'block';
            } else {
                alert('Please fill out all required shipping details.');
            }
        });
    </script>
</body>
</html>