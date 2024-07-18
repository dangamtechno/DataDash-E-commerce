<?php
require_once '../../backend/utils/session.php';
require '../../backend/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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

function getAddressById($addressId) {
    $conn = new mysqli("localhost", "root", "", "datadash");

    $sql = "SELECT * FROM addresses WHERE address_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $addressId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to retrieve a specific payment method by ID
function getPaymentMethodById($paymentMethodId) {
    $conn = new mysqli("localhost", "root", "", "datadash");

    $sql = "SELECT * FROM payment_methods WHERE payment_method_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paymentMethodId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to create a new order
function createOrder($userId, $selectedProducts, $selectedQuantities, $shippingAddressId, $paymentMethodId, $discountAmount) {
    $conn = new mysqli("localhost", "root", "", "datadash");

    $orderDate = date('Y-m-d H:i:s');
    $totalPrice = 0;

    // Begin transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Calculate total price and check inventory before order creation
        foreach ($selectedProducts as $productId) {
            $quantity = $selectedQuantities[$productId];
            // Fetch price and quantity from the `inventory` table
            $sql = "SELECT p.price, i.quantity FROM product p JOIN inventory i ON p.product_id = i.product_id WHERE p.product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['quantity'] >= $quantity) {
                $productPrice = $row['price'];
                $totalPrice += $productPrice * $quantity;
            } else {
                // Insufficient inventory
                throw new Exception("Insufficient inventory for product ID: " . $productId);
            }
        }

        // Insert order into orders table
        $sql = "INSERT INTO orders (user_id, order_date, total_amount, status)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $statusp = 'processing';
        $total_amount = $totalPrice - $discountAmount;
        $stmt->bind_param("isds", $userId, $orderDate, ($total_amount), $statusp);
        $stmt->execute();

        // Get the order ID
        $orderId = $conn->insert_id;

        // Insert order details into order_items table
        foreach ($selectedProducts as $productId) {
            $quantity = $selectedQuantities[$productId];
            $sql = "SELECT price FROM product WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $productPrice = $row['price'];

            $sql = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, status, order_date)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $orderStatus = 'processing';
            $stmt->bind_param("iiidss", $orderId, $productId, $quantity, $productPrice, $orderStatus, $orderDate);
            $stmt->execute();

            // Update inventory for the specific product
            $sql = "UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $quantity, $productId);
            $stmt->execute();
        }

        // Remove items from cart (for selected products)
        foreach ($selectedProducts as $productId) {
            $sql = "DELETE FROM cart_product WHERE cart_id IN (SELECT cart_id FROM cart WHERE user_id = ?) AND product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $productId);
            $stmt->execute();
        }

        $conn->commit(); // Commit transaction if everything succeeded

        // Send order summary email
        if (sendOrderSummaryEmail($userId, $orderId, $totalPrice, $discountAmount)) {
            echo '<p class="success-message">A summary of your order has been sent to the email in file.</p>';
        } else {
            echo '<p>Order placed, but failed to send email summary.</p>';
        }

    } catch (Exception $e) {
        // Rollback transaction if any error occurred
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}

function sendOrderSummaryEmail($userId, $orderId, $totalPrice, $discountAmount) {
    $conn = new mysqli("localhost", "root", "", "datadash");
    
    // Fetch user email
    $sql = "SELECT email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userEmail = $user['email'];
    $stmt->close();

    // Fetch order details
    $sql = "SELECT oi.product_id, p.name, oi.quantity, oi.unit_price, p.image
            FROM order_items oi
            JOIN product p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orderDetails = "";
    while ($row = $result->fetch_assoc()) {
        $orderDetails .= "<tr>";
        $orderDetails .= "<td>" . $row['name'] . "</td>";
        $orderDetails .= "<td>" . $row['quantity'] . "</td>";
        $orderDetails .= "<td>$" . number_format($row['unit_price'], 2) . "</td>";
        $orderDetails .= "<td>$" . number_format($row['unit_price'] * $row['quantity'], 2) . "</td>";
        $orderDetails .= "</tr>";
    }

    $subtotal = $totalPrice;
    $shipping = 0.00; // Assuming free shipping
    $finalTotal = $subtotal - $discountAmount + $shipping;

    $subject = "Your Order Has Been Placed";

    $message = "
    <html>
    <head>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 15px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            img {
                max-width: 100px;
                height: auto;
            }
            .footer {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #ccc;
                font-size: 0.9em;
                color: #666;
            }
            .footer h1 {
                font-size: 1.2em;
            }
        </style>
    </head>
    <body>
        <h2>Thank you for your order, " . getSessionUsername() . "!</h2>
        <p>Order ID: " . $orderId . "</p>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                " . $orderDetails . "
            </tbody>
            <tfoot>
                <tr>
                    <th colspan='3'>Subtotal:</th>
                    <th>$" . number_format($subtotal, 2) . "</th>
                </tr>";

    if ($discountAmount > 0) {
        $message .= "
                <tr>
                    <th colspan='3'>Discount:</th>
                    <th style=color: green>-$" . number_format($discountAmount, 2) . "</th>
                </tr>";
    }

    $message .= "
                <tr>
                    <th colspan='3'>Shipping:</th>
                    <th>$" . number_format($shipping, 2) . "</th>
                </tr>
                <tr>
                    <th colspan='3'>Total:</th>
                    <th><h3 id='total-price'>$" . number_format($finalTotal, 2) . "</h3></th>
                </tr>
            </tfoot>
        </table>
        <div class='footer'>
            <h1>Thank you for choosing Datadash Inc.</h1>
            <p>This email was sent from a notification-only email address that does not accept incoming emails. Do not reply to this message.</p>
        </div>
    </body>
    </html>";

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Set who the message is to be sent from
        $mail->setFrom('no-reply@datadash.com', 'Datadash');
        
        // Set who the message is to be sent to
        $mail->addAddress($userEmail);
        
        // Set the subject line
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->isHTML(true); // Set email format to HTML

        $mail->send();
        return true;
    } 
    catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
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

        .order-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .order-details p {
            margin: 5px 0;
        }

        .continue-shopping-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .continue-shopping-btn:hover {
            background-color: #21618C;
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

        .button-container {
            display: flex; /* Enable flexbox */
        justify-content: center; /* Center content horizontally */
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

        .success-message {
            color: green;
            font-weight: bold;
            margin-bottom: 40px;
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
        <div class="checkout-container">
            <h2>Checkout</h2>
            <?php
            if (sessionExists()) {
                $userId = getSessionUserId();
                $cartItems = getCartItems($userId);

                if (!empty($cartItems)) {
                    $selectedProductIds = [];
                    $selectedQuantities = [];

                    $couponCode = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : '';

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
                                            $discountAmount = 0;
                                            if (!empty($couponCode)) {
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
                                <?php
                                if (sessionExists()) {
                                    $userId = getSessionUserId();

                                    $shippingAddressId = $_POST['shipping_address_id'];
                                    $paymentMethodId = $_POST['payment_method_id'];

                                    $shippingAddress = getAddressById($shippingAddressId);
                                    $paymentMethod = getPaymentMethodById($paymentMethodId);
                                }
                                ?>
                                <h3>Shipping Address</h3>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label style="border-radius: 30px;" for="shipping-address-<?php echo $shippingAddress['address_id']; ?>">
                                            <?php echo $shippingAddress['street_address'] . ', ' . $shippingAddress['city'] . ', ' . $shippingAddress['state'] . ', ' . $shippingAddress['postal_code'] . ', ' . $shippingAddress['country']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="checkout-section">
                                <h3>Payment Information</h3>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label style="border-radius: 30px;" for="payment-method-<?php echo $paymentMethod['payment_method_id']; ?>">
                                            <?php echo $paymentMethod['method_type'] . ' (Ending in ' . substr($paymentMethod['card_number'], -4) . ')'; ?>
                                            <br>
                                            <?php echo 'CVV: ' . $paymentMethod['cvs_number'] . ', Expiration: ' . $paymentMethod['expiration_date']; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            // Check if the form has been submitted
                            if (isset($_POST['place_order'])) {
                                // Call the createOrder function after validating product selection
                                if (isset($_POST['selected_products']) && !empty($selectedProductIds)) {
                                    createOrder($userId, $selectedProductIds, $selectedQuantities, $shippingAddressId, $paymentMethodId, $discountAmount);
                                    deactivateCoupon($couponCode);
                                    echo '<p class="success-message">Thank you for your order!</p>';
                                } else {
                                    echo '<p>Invalid product selection.</p>';
                                }
                            } else {
                                // Display the order summary
                                ?>
                                <div class="button-container">
                                  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="selected_products" value='<?php echo json_encode($selectedProductIds); ?>' />
                                    <input type="hidden" name="shipping_address_id" value="<?php echo $shippingAddressId; ?>" />
                                    <input type="hidden" name="coupon_code" value="<?php echo $couponCode; ?>" />
                                    <input type="hidden" name="payment_method_id" value="<?php echo $paymentMethodId; ?>" />
                                    <button type="submit" name="place_order" id="place-order-button" style="
                                    background-color: #009dff;
                                    color: white;
                                    padding: 10px 20px;
                                    border: none;
                                    border-radius: 30px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    transition: background-color 0.3s ease;
                                  ">
                                    Place Order
                                  </button>
                                  </form>
                                </div>
                                <?php
                            }
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
</html>