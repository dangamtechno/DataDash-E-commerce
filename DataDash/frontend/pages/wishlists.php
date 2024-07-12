<?php
require_once '../../backend/utils/session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!sessionExists()) {
    echo "<p class='error-message'>You must be logged in to view your wishlist.</p>";
    echo "<a href='login_page.php'>Login</a>";
    exit;
}

// Get user ID from the session
$user_id = getSessionUserId();

// Fetch wishlists for the current user
$wishlists = [];
if (sessionExists()) {
    $stmt = $conn->prepare("SELECT w.wishlist_id, w.wishlist_name, COUNT(wp.product_id) AS product_count 
                            FROM wishlist w 
                            LEFT JOIN wishlist_products wp ON w.wishlist_id = wp.wishlist_id 
                            WHERE w.user_id = ? 
                            GROUP BY w.wishlist_id");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $wishlists[] = $row;
    }
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
    <title>Wishlist</title>

    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .shop-button-container {
            text-align: center; /* Center the button horizontally */
            margin-top: 10px; /* Add some space above the button */
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Add shadow */
        }

        th, td {
            padding: 12px 15px; /* Increased padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Light grey alternate rows */
        }

        /* Link Styles */
        td a {
            color: black; 
            text-decoration: none; /* Remove underline */
        }

        td a:hover {
            text-decoration: underline; /* Underline on hover */
        }

        /* Heading Styles */
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 5px 10px;
            font-size: 14px;
            color: #fff;
            background-color: #009dff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-buttons button.delete {
            background-color: #ff4d4d; /* Red color for delete */
        }

        .add-wishlist-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .add-wishlist-form input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            margin-right: 10px;
        }

        .add-wishlist-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #009dff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-wishlist-form button:hover {
            background-color: #0056b3;
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
    <div class="container">
        <h2>Wishlists</h2>
        <?php if (count($wishlists) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Wishlist Name</th>
                    <th>Number of Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wishlists as $wishlist): ?>
                    <tr>
                        <td><a href="wishlist_details.php?wishlist_id=<?php echo $wishlist['wishlist_id']; ?>"><?php echo htmlspecialchars($wishlist['wishlist_name']); ?></a></td>
                        <td>
                            <?php
                            if ($wishlist['product_count'] < 1) {
                                echo '<a href="wishlist_details.php?wishlist_id=' . $wishlist['wishlist_id'] . '">Empty</a>';
                            } else {
                                echo '<a href="wishlist_details.php?wishlist_id=' . $wishlist['wishlist_id'] . '">' . $wishlist['product_count'] . '</a>';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="../../backend/utils/rename_wishlist.php" method="post" class="rename-form" id="rename-form-<?php echo $wishlist['wishlist_id']; ?>" style="display: inline;">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $wishlist['wishlist_id']; ?>">
                                    <input type="text" name="new_name" placeholder="New Wishlist Name" style="display: none;">
                                    <button type="button" class="rename" onclick="showRenameInput(this, <?php echo $wishlist['wishlist_id']; ?>)">Rename</button>
                                </form>
                                <form action="../../backend/utils/delete_wishlist.php" method="post" style="display: inline;">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $wishlist['wishlist_id']; ?>">
                                    <button type="submit" class="delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No wishlists found.</p>
        <?php endif; ?>
        <div class="add-wishlist-form">
            <form action="../../backend/utils/create_wishlist.php" method="post">
                <input type="text" name="wishlist_name" placeholder="New Wishlist Name" required>
                <button type="submit">Add Wishlist</button>
            </form>
        </div>
    </div>

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
<script src="../js/payment_methods.js"></script>
<script src="../js/search.js"></script>
<script>
    function showRenameInput(button, wishlistId) {
        const form = document.getElementById(`rename-form-${wishlistId}`);
        const input = form.querySelector('input[name="new_name"]');
        if (input.style.display === 'none' || input.style.display === '') {
            input.style.display = 'inline-block';
            input.focus();
            button.innerHTML = 'Submit';
            
        } else {
            form.submit();
        }
    }
</script>

</body>
</html>
