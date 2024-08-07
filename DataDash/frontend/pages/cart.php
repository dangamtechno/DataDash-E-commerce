<?php
require_once '../../backend/utils/session.php';

$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Database functions
function getCartItems($userId) {
    global $conn;

    $sql = "SELECT p.product_id, p.name, p.price, p.image, cp.quantity, i.quantity AS inventory_quantity
            FROM product p
            INNER JOIN cart_product cp ON p.product_id = cp.product_id
            INNER JOIN cart c ON cp.cart_id = c.cart_id
            INNER JOIN inventory i ON p.product_id = i.product_id
            WHERE c.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = $result->fetch_all(MYSQLI_ASSOC);

    return $cartItems;
}

function removeFromCart($userId, $productId) {
    global $conn;

    $sql = "DELETE FROM cart_product
            WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = ?)
            AND product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $productId);

    return $stmt->execute();
}

function updateCartQuantity($userId, $productId, $newQuantity) {
    global $conn;

    // Check if the new quantity is available in the inventory
    $sql = "SELECT quantity FROM inventory WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $inventoryQuantity = $result->fetch_assoc()['quantity'];

    if ($newQuantity <= $inventoryQuantity) {
        $sql = "UPDATE cart_product
                SET quantity = ?
                WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = ?)
                AND product_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $newQuantity, $userId, $productId);

        return $stmt->execute();
    } else {
        return false; // Quantity exceeds inventory
    }
}

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $userId = getSessionUserId();

    if ($action === 'remove' && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];
        if (removeFromCart($userId, $productId)) {
            header("Location: cart.php?removed=true");
            exit();
        } else {
            header("Location: cart.php?removed=false");
            exit();
        }
    } elseif ($action === 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $newQuantity = $_POST['quantity'];
        if (updateCartQuantity($userId, $productId, $newQuantity)) {
            header("Location: cart.php?updated=true");
            exit();
        } else {
            header("Location: cart.php?updated=false");
            exit();
        }
    }
}
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

    <title>Shopping Cart</title>
    <style>
        .cart-table img {
            max-width: 100%;
            height: auto;
            width: 275px;
            height: 275px;
            object-fit: contain;
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

        .cart-table input[type="checkbox"] {
            margin-right: 5px;
        }

        .checkout-button {
            background-color: #009dff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
        }

        .checkout-button:hover {
            background-color: #0056b3;
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
        <div class="cart-container">
            <div class="cart-header">
                <h2>Shopping Cart</h2>
            </div>

            <?php
            if (sessionExists()) {
                $userId = getSessionUserId();
                $cartItems = getCartItems($userId);

                if (!empty($cartItems)) {
                    ?>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"> Select All</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalPrice = 0;
                            foreach ($cartItems as $item) {
                                $totalPrice += ($item['price'] * $item['quantity']);
                                ?>
                                <tr>
                                    <td><input type="checkbox" class="select-item" data-product-id="<?php echo $item['product_id']; ?>" data-price="<?php echo $item['price']; ?>"></td>
                                    <td>
                                        <img src="../images/electronic_products/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                                        <?php echo $item['name']; ?>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <form action="cart.php" method="post">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['inventory_quantity']; ?>">
                                            <button type="submit">Update</button>
                                        </form>
                                        <?php if ($item['quantity'] > $item['inventory_quantity']): ?>
                                            <span class="error-message">Quantity exceeds available inventory.</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <form action="cart.php" method="post">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <button type="submit" class="remove-button"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="cart-total">
                        <h3>Total: $<span id="total-price"><?php echo number_format($totalPrice, 2); ?></span></h3>
                        <br>
                        <form action="checkout.php" method="post" id="checkout-form">
                            <input type="hidden" name="action" value="checkout">
                            <input type="hidden" name="selected_products" id="selected-products">
                            <input type="hidden" name="selected_quantities" id="selected-quantities">
                            <button type="submit" class="checkout-button">Proceed to Checkout</button>
                        </form>
                    </div>
                    <?php
                } else {
                    echo '<p class="empty-cart">Your cart is empty.</p>';
                }
            } else {
                echo '<p class="empty-cart">Please log in to view your cart.</p>';
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
<script src="../js/navbar.js"></script>
<script src="../js/slider.js"></script>
<script src="../js/search.js"></script>
<script src="../js/cart_selection.js"></script>
</body>
</html>