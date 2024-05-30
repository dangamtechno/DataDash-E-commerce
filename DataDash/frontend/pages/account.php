<?php
require_once '../../backend/utils/session.php';
require_once '../../backend/include/database_config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Header */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left-heading {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 50px;
        }

        .login-status {
            margin-left: 20px;
        }

        .search-form {
            display: flex;
            align-items: center;
        }

        .search-form input[type="search"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .search-form input[type="submit"] {
            padding: 5px 10px;
            background-color: #555;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .navigation ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navigation li {
            margin-right: 20px;
        }

        .navigation a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
        }

        /* Main Content */
        main {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        section {
            margin-bottom: 40px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="heading">
            <div class="left-heading">
                <div class="logo">
                    <img src="../images/DataDash.png" alt="Logo">
                </div>
                <div class="login-status">
                    <?php if (sessionExists()): ?>
                        <p class="hello-message">Hello, <?php echo getSessionUsername(); ?>!</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right-heading">
                <nav class="navigation">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">Cart</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
                <form class="search-form">
                    <label>
                        <input type="search" placeholder="Search">
                    </label>
                    <input type="submit" value="Search">
                </form>
            </div>
        </div>

    </header>

    <main>
        <?php if (sessionExists()): ?>
            <!-- User Profile -->
            <section>
                <h2>User Profile</h2>
                <?php
                $conn = new mysqli("localhost", "root", "", "datadash");
                $userId = getSessionUserId();
                $query = "SELECT * FROM users WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                ?>
                <p>Name: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
                <p>Email: <?php echo $user['email']; ?></p>
                <!-- Provide options to update profile picture, change password, etc. -->
            </section>

            <!-- Order History -->
            <section>
                <h2>Order History</h2>
                <?php
                $query = "SELECT * FROM orders WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($order = $result->fetch_assoc()) {
                        echo "<div>";
                        echo "<p>Order ID: " . $order['id'] . "</p>";
                        echo "<p>Order Date: " . $order['order_date'] . "</p>";
                        echo "<p>Total: " . $order['total'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No orders found.</p>";
                }
                ?>
                <!-- Provide options to view order details, track shipment, initiate returns/refunds -->
            </section>

            <!-- Saved Addresses -->
            <section>
                <h2>Saved Addresses</h2>
                <?php
                $query = "SELECT * FROM addresses WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($address = $result->fetch_assoc()) {
                        echo "<div>";
                        echo "<p>" . $address['address_line_1'] . "</p>";
                        echo "<p>" . $address['address_line_2'] . "</p>";
                        echo "<p>" . $address['city'] . ", " . $address['state'] . " " . $address['zip_code'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No addresses found.</p>";
                }
                ?>
                <!-- Provide options to add, edit, or delete addresses -->
                <!-- Set default addresses -->
            </section>

            <!-- Payment Methods -->
            <section>
                <h2>Payment Methods</h2>
                <?php
                $query = "SELECT * FROM payment_methods WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($paymentMethod = $result->fetch_assoc()) {
                        echo "<div>";
                        echo "<p>Card Type: " . $paymentMethod['card_type'] . "</p>";
                        echo "<p>Card Number: " . $paymentMethod['card_number'] . "</p>";
                        echo "<p>Expiration Date: " . $paymentMethod['expiration_date'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No payment methods found.</p>";
                }
                ?>
                <!-- Provide options to add, edit, or delete payment methods -->
                <!-- Set default payment method -->
            </section>

            <!-- Wishlist -->
            <section>
                <h2>Wishlist</h2>
                <?php
                $query = "SELECT p.* FROM wishlists w JOIN product p ON w.product_id = p.product_id WHERE w.user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        echo "<div>";
                        echo "<p>" . $product['name'] . "</p>";
                        echo "<p>Price: " . $product['price'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No items in your wishlist.</p>";
                }
                ?>
                <!-- Provide options to remove items or move them to the cart -->
            </section>

            <!-- Account Settings -->
            <section>
                <h2>Account Settings</h2>
                <!-- Provide options to update personal information, change password, manage marketing preferences, account security, subscriptions, etc. -->
            </section>

            <!-- Logout -->
            <a href="../../backend/utils/logout.php">Logout</a>
        <?php else: ?>
            <p>You need to be logged in to access this page.</p>
        <?php endif; ?>
    </main>

    <footer>
        <!-- Footer content goes here -->
    </footer>
</body>
</html>
