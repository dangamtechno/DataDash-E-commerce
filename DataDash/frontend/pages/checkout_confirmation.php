<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the order ID is provided in the URL
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Retrieve order details from the database
    $sql = "SELECT o.order_id, o.order_date, o.total_amount, od.product_id, p.name, p.image, od.quantity, od.unit_price
            FROM orders o
            JOIN order_details od ON o.order_id = od.order_id
            JOIN product p ON od.product_id = p.product_id
            WHERE o.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $orderDetails = $result->fetch_all(MYSQLI_ASSOC);

        // Update inventory for the ordered products
        foreach ($orderDetails as $detail) {
            $productId = $detail['product_id'];
            $quantity = $detail['quantity'];

            // Check if the product has enough stock
            $sql = "SELECT quantity FROM inventory WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentStock = $row['quantity'];

                if ($currentStock >= $quantity) {
                    // Update inventory
                    $sql = "UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $quantity, $productId);
                    $stmt->execute();
                } else {
                    echo "Insufficient stock for product: " . $detail['name'] . "<br>";
                }
            } else {
                echo "Product not found in inventory: " . $detail['name'] . "<br>";
            }
        }
    } else {
        echo "Invalid order ID.";
        exit;
    }
} else {
    echo "Order ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Order Confirmation</title>
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
        <div class="confirmation-container">
            <h2>Order Confirmation</h2>
            <p>Thank you for your order!</p>

            <h3>Order Details</h3>
            <table class="order-details-table">
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
                    $totalAmount = 0;
                    foreach ($orderDetails as $detail) {
                        $productTotal = $detail['quantity'] * $detail['unit_price'];
                        $totalAmount += $productTotal;
                        ?>
                        <tr>
                            <td>
                                <img src="../images/<?php echo $detail['image']; ?>" alt="<?php echo $detail['name']; ?>" class="product-image">
                                <?php echo $detail['name']; ?>
                            </td>
                            <td><?php echo $detail['quantity']; ?></td>
                            <td>$<?php echo number_format($detail['unit_price'], 2); ?></td>
                            <td>$<?php echo number_format($productTotal, 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total Amount:</th>
                        <th>$<?php echo number_format($totalAmount, 2); ?></th>
                    </tr>
                </tfoot>
            </table>

            <p>Your order will be processed and shipped soon. You will receive an email with tracking information once your order is shipped.</p>
            <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
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