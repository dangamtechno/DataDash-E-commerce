<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';

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
        /* ... same CSS as in the previous example ... */
        .cart-table img {
            max-width: 100%;
            height: auto;
            width: 275px;
            height: 275px;
            object-fit: contain;
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

        .cart-table input[type="checkbox"] {
            margin-right: 5px;
        }

        .checkout-button {
            background-color: #009dff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .checkout-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
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
                echo '<p class="empty-cart">Please <a href="login_page.php">log in</a> to view your cart.</p>';
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
        // Select All functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const selectItemCheckboxes = document.querySelectorAll('.select-item');
        const totalPriceElement = document.getElementById('total-price');

        selectAllCheckbox.addEventListener('change', () => {
            selectItemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                updateTotalPrice();
                updateSelectedProducts();
            });
        });

        // Update selected products on checkbox change
        function updateSelectedProducts() {
            const selectedProductIds = [];
            const selectedQuantities = {};
            selectItemCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const productId = checkbox.dataset.productId;
                    const quantityInput = document.querySelector(`input[name="quantity[${productId}]"]`);
                    const quantity = parseInt(quantityInput.value);
                    selectedProductIds.push(productId);
                    selectedQuantities[productId] = quantity;
                }
            });

            // Update hidden input fields with selected product data
            const selectedProductsInput = document.getElementById('selected-products');
            selectedProductsInput.value = JSON.stringify(selectedProductIds);

            const selectedQuantitiesInput = document.getElementById('selected-quantities');
            selectedQuantitiesInput.value = JSON.stringify(selectedQuantities);
        }

        selectItemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedProducts);
            checkbox.addEventListener('change', updateTotalPrice);
        });

        // Update total price based on selected items
        function updateTotalPrice() {
            let selectedPrice = 0;
            selectItemCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedPrice += parseFloat(checkbox.dataset.price);
                }
            });
            totalPriceElement.textContent = selectedPrice.toFixed(2);
        }

        // Initial total price (when no items are selected)
        updateTotalPrice();
    </script>
</body>
</html>