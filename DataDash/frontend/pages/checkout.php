<?php
require_once '../../backend/utils/session.php';

// Establish database connection using the configured credentials
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve cart items for a given user ID
function getCartItems($userId) {

    $conn = new mysqli("localhost", "root", "", "datadash");

    $sql = "SELECT cp.product_id, p.name, p.price, p.image, cp.quantity
            FROM cart_product cp
            JOIN product p ON cp.product_id = p.product_id
            JOIN cart c ON cp.cart_id = c.cart_id
            WHERE c.user_id = ?";
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

// Function to validate coupon code
function validateCoupon($couponCode) {

    $conn = new mysqli("localhost", "root", "", "datadash");

    $sql = "SELECT discount_amount FROM coupons WHERE coupon_code = ? AND expiration_date >= CURDATE() AND active = TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $couponCode);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $coupon = $result->fetch_assoc();
        $stmt->close();
        return $coupon['discount_amount'];
    }
    $stmt->close();
    return false;
}

//Function to deactivate coupon code
function deactivateCoupon($couponCode){

    $conn = new mysqli("localhost", "root", "", "datadash");

    $sql = "UPDATE coupons SET active = FALSE WHERE coupon_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $couponCode);
        $stmt->execute();
        $stmt->close();

}

// Function to retrieve user's shipping addresses
function getUserShippingAddresses($userId) {

    $conn = new mysqli("localhost", "root", "", "datadash");

    $sql = "SELECT address_id, street_address, city, state, postal_code, country 
            FROM addresses 
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $addresses = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }
    }
    return $addresses;
}

// Function to retrieve user's payment methods
function getUserPaymentMethods($userId) {

    $conn = new mysqli("localhost", "root", "", "datadash");

    // IMPORTANT!
    // Do NOT store full credit card numbers in your database.
    // This is just for demonstration; replace with your payment gateway integration.
    $sql = "SELECT payment_method_id, method_type, card_number, cvs_number, expiration_date 
            FROM payment_methods 
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $paymentMethods = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $paymentMethods[] = $row;
        }
    }
    return $paymentMethods;
}
$conn->close();

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
        /* Style for the checkout page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .checkout-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .checkout-section {
            margin-bottom: 20px;
        }

        .checkout-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        .checkout-table th, .checkout-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .checkout-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .checkout-table img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .checkout-button {
            background-color: #009dff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .checkout-button:hover {
            background-color: #0056b3;
        }

        .order-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .order-details p {
            margin: 5px 0;
        }


        /* Style for radio button labels */
        .form-group label {
            display: block;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .form-group label:hover {
            background-color: #f0f0f0;
        }

        /* Style for "Create New" buttons */
        .checkout-section button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: block;
            margin: 10px auto;
            width: fit-content;
        }

        .checkout-section button:hover {
            background-color: #0056b3;
        }

        /* Ensure shop button styles are consistent with homepage */
        .shop-button-container {
            text-align: center;
            margin-top: 10px;
        }

        .shop-button {
            display: inline-block;
            padding: 10px 40px;
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

    </style>
</head>
<body>
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
                        $selectedProductsArray = json_decode($_POST['selected_products'], true);
                        if (isset($selectedProductsArray) && !empty($selectedProductsArray)) {
                            foreach ($cartItems as $item) {
                                if (in_array($item['product_id'], $selectedProductsArray)) {
                                    $selectedProductIds[] = $item['product_id'];
                                    $selectedQuantities[$item['product_id']] = $item['quantity'];
                                }
                            }
                            ?>
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
                                                        <img src="../images/electronic_products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                                                        <?php echo $product['name']; ?>
                                                    </td>
                                                    <td><?php echo $quantity; ?></td>
                                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                    <td>$<?php echo number_format($productTotal, 2); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Subtotal:</th>
                                            <th>$<?php echo number_format($totalPrice, 2); ?></th>
                                        </tr>
                                        <?php
                                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'apply_coupon') {
                                                $couponCode = $_POST['coupon_code'];
                                                $discountAmount = validateCoupon($couponCode);
                                                if ($discountAmount !== false) {
                                                    $totalPrice -= $discountAmount;
                                                    echo "<tr>
                                                        <th colspan='3'>Discount:</th>
                                                        <th>$" . number_format($discountAmount, 2) . "</th>
                                                    </tr>";
                                                }
                                            }
                                            ?>
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
                                <h3>Apply Coupon</h3>
                                <form action="checkout.php" method="post" id="coupon-form">
                                    <input type="hidden" name="action" value="apply_coupon">
                                    <input type="hidden" name="selected_products" value="<?php echo json_encode($selectedProductIds); ?>">
                                    <input type="hidden" name="selected_quantities" value="<?php echo json_encode($selectedQuantities); ?>">
                                    <div class="form-group">
                                        <label for="coupon-code">Coupon Code:</label>
                                        <input type="text" name="coupon_code" id="coupon-code" required>
                                    </div>
                                    <button type="submit" class="checkout-button">Apply Coupon</button>
                                    <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'apply_coupon') {
                                    $couponCode = $_POST['coupon_code'];
                                    $discountAmount = validateCoupon($couponCode);
                                    if ($discountAmount !== false) {
                                        deactivateCoupon($couponCode);
                                        echo '<p style="color: green;">Coupon applied! Discount: $' . number_format($discountAmount, 2) . '</p>';
                                    } else {
                                        echo '<p style="color: red;">Invalid or expired coupon code.</p>';
                                    }
                                }
                                
                            ?>
                                </form>
                            </div>

                            <div class="checkout-section">
                                <h3>Shipping Address</h3>
                                <form action="review_order.php" method="post" id="checkout-form">
                                    <input type="hidden" name="action" value="checkout">
                                    <input type="hidden" name="selected_products" value="<?php echo json_encode($selectedProductIds); ?>">
                                    <input type="hidden" name="selected_quantities" value="<?php echo json_encode($selectedQuantities); ?>">
                                    <input type="hidden" name="shipping_address_id" id="shipping_address_id" value="">
                                    <input type="hidden" name="payment_method_id" id="payment_method_id" value="">

                                    <div class="form-group">
                                        <label for="shipping-address">Select Shipping Address:</label>
                                        <div class="form-group">
                                            <?php
                                            $shippingAddresses = getUserShippingAddresses($userId);
                                            if (!empty($shippingAddresses)) {
                                                foreach ($shippingAddresses as $address) {
                                                    ?>
                                                    <div class="form-group">
                                                        <input type="radio" id="shipping-address-<?php echo $address['address_id']; ?>" name="shipping-address" value="<?php echo $address['address_id']; ?>" required>
                                                        <label for="shipping-address-<?php echo $address['address_id']; ?>">
                                                            <?php echo $address['street_address'] . ', ' . $address['city'] . ', ' . $address['state'] . ', ' . $address['postal_code'] . ', ' . $address['country']; ?>
                                                        </label>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <p>You have no saved addresses. You can add an address below.</p>
                                                <button type="button" onclick="window.location.href='saved_addresses.php'">Create New Address</button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="checkout-section">
                                        <h3>Payment Information</h3>
                                        <div class="form-group">
                                            <label for="payment-method">Select Payment Method:</label>
                                            <div class="form-group">
                                                <?php
                                                $paymentMethods = getUserPaymentMethods($userId);
                                                if (!empty($paymentMethods)) {
                                                    foreach ($paymentMethods as $method) {
                                                        ?>
                                                        <div class="form-group">
                                                            <input type="radio" id="payment-method-<?php echo $method['payment_method_id']; ?>" name="payment-method" value="<?php echo $method['payment_method_id']; ?>" required>
                                                            <label for="payment-method-<?php echo $method['payment_method_id']; ?>">
                                                                <?php echo $method['method_type'] . ' (Ending in ' . substr($method['card_number'], -4) . ')'; ?>
                                                                <br>
                                                                <?php echo 'CVV: ' . $method['cvs_number'] . ', Expiration: ' . $method['expiration_date']; ?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <p>You have no saved payment methods. You can add a payment method below.</p>
                                                    <button type="button" onclick="window.location.href='payment_methods.php'">Create New Payment Method</button>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <br><br>
                                            <button type="submit" id="review-order-button">Review Order</button>
                                    </div>
                                </form>
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
                echo '<p>Please log in to proceed to checkout.</p>';
            }
            ?>
        </div>
    </main>
    <script>
        // Disable Review Order button initially
        document.getElementById('review-order-button').disabled = true;

        // Add event listeners to radio buttons
        const shippingAddressRadios = document.querySelectorAll('input[name="shipping-address"]');
        const paymentMethodRadios = document.querySelectorAll('input[name="payment-method"]');

        // Check if all required fields are filled out
        function checkRequiredFields() {
            let allFieldsFilled = true;

            // Check shipping address fields
            if (shippingAddressRadios.length > 0) {
                if (!document.querySelector('input[name="shipping-address"]:checked')) {
                    allFieldsFilled = false;
                }
            }

            // Check payment method fields
            if (paymentMethodRadios.length > 0) {
                if (!document.querySelector('input[name="payment-method"]:checked')) {
                    allFieldsFilled = false;
                }
            }

            // Enable Review Order button if all fields are filled
            if (allFieldsFilled) {
                document.getElementById('review-order-button').disabled = false;
            } else {
                document.getElementById('review-order-button').disabled = true;
            }
        }

        // Add event listeners to radio buttons to enable Review Order button
        shippingAddressRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                document.getElementById('shipping_address_id').value = radio.value;
                checkRequiredFields();
            });
        });

        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                document.getElementById('payment_method_id').value = radio.value;
                checkRequiredFields();
            });
        });
    </script>
</body>
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
<script src="../js/search.js"></script>
</html>


