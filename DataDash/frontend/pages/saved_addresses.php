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
    echo "<p class='error-message'>You must be logged in to reset your password.</p>";
    echo "<a href='login_page.php'>Login</a>";
    exit;
}

// Get user_id from the sessions table
$sql1 = "SELECT user_id FROM users WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id)";
$result = $conn->query($sql1);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    // Get saved addresses from database
    $sql2 = "SELECT * FROM addresses WHERE user_id = '$user_id'";
    $addresses_result = $conn->query($sql2);
} else {
    echo "User not found in the sessions table.";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_address'])) {
        // Add new address
        $address_type = $_POST['address_type'];
        $street_address = $_POST['street_address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];

        $sql = "INSERT INTO addresses (user_id, address_type, street_address, city, state, postal_code, country)
                VALUES ('$user_id', '$address_type', '$street_address', '$city', '$state', '$postal_code', '$country')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>New address added successfully.</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_address'])) {
        // Update existing address
        $address_id = $_POST['address_id'];
        $address_type = $_POST['address_type'];
        $street_address = $_POST['street_address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];

        $sql = "UPDATE addresses SET address_type = '$address_type', street_address = '$street_address', city = '$city',
                     state = '$state', postal_code = '$postal_code', country = '$country' WHERE address_id = '$address_id' AND user_id = '$user_id'";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>Address updated successfully.</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Delete address
if (isset($_GET['delete_id'])) {
    $address_id = $_GET['delete_id'];

    $sql = "DELETE FROM addresses WHERE address_id = '$address_id' AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='success-message'>Address deleted successfully.</div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
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

    <title>Saved Addresses</title>

]    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Heading Styles */
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form Styles */
        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #680eea;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Success Message Styles */
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }

        /* No Addresses Message Styles */
        .no-addresses {
            text-align: center;
            font-style: italic;
            color: #888;
            margin-bottom: 20px;
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
    <div class="container">
        <h2>Saved Addresses</h2>

        <?php if ($addresses_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Address ID</th>
                    <th>Address Type</th>
                    <th>Street Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Postal Code</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $addresses_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['address_id']; ?></td>
                        <td><?php echo $row['address_type']; ?></td>
                        <td><?php echo $row['street_address']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td><?php echo $row['postal_code']; ?></td>
                        <td><?php echo $row['country']; ?></td>
                        <td>
                            <a href="#" onclick="showEditForm(<?php echo $row['address_id']; ?>, '<?php echo $row['address_type']; ?>', '<?php echo $row['street_address']; ?>', '<?php echo $row['city']; ?>', '<?php echo $row['state']; ?>', '<?php echo $row['postal_code']; ?>', '<?php echo $row['country']; ?>'); return false;" style="color: #007bff; "> <i class="fas fa-edit"></i> Edit</a> |
                            <a href="?delete_id=<?php echo $row['address_id']; ?>" onclick="return confirm('Are you sure you want to delete this address?')" style="color: #dc3545;"> <i class="fas fa-trash-alt"></i> Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-addresses">You have no saved addresses.</p>
        <?php endif; ?>

        <!-- Add New Address Form -->
        <h3>Add New Address</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="add-address-form">
            <input type="hidden" name="add_address" value="1">
            <label for="address_type">Address Type (Home, Business, etc.):</label>
            <input type="text" style="border-radius: 30px;" name="address_type" required>

            <label for="street_address">Street Address:</label>
            <input type="text" style="border-radius: 30px;" name="street_address" required>

            <label for="city">City:</label>
            <input type="text" style="border-radius: 30px;" name="city" required>

            <label for="state">State:</label>
            <input type="text" style="border-radius: 30px;" name="state" required>

            <label for="postal_code">Postal Code:</label>
            <input type="text" style="border-radius: 30px;" name="postal_code" required>

            <label for="country">Country:</label>
            <input type="text" style="border-radius: 30px;" name="country" required>

            <input type="submit" style="border-radius: 30px;" value="Add Address" id="add-address-submit">
        </form>

        <!-- Edit Address Form -->
        <div id="edit-address-form" style="display: none;">
            <h3>Edit Address</h3>
            <form id="edit-address-popup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="update_address" value="1">
                <input type="hidden" name="address_id" style="border-radius: 30px;" id="edit-address-id">
                <label for="edit-address-type">Address Type (Home, Business, etc.):</label>
                <input type="text" name="address_type" style="border-radius: 30px;" id="edit-address-type" required>

                <label for="edit-street-address">Street Address:</label>
                <input type="text" name="street_address" style="border-radius: 30px;" id="edit-street-address" required>

                <label for="edit-city">City:</label>
                <input type="text" name="city" style="border-radius: 30px;" id="edit-city" required>

                <label for="edit-state">State:</label>
                <input type="text" name="state" style="border-radius: 30px;" id="edit-state" required>

                <label for="edit-postal-code">Postal Code:</label>
                <input type="text" name="postal_code" style="border-radius: 30px;" id="edit-postal-code" required>

                <label for="edit-country">Country:</label>
                <input type="text" name="country" style="border-radius: 30px;" id="edit-country" required>

                <input style="border-radius: 30px;" type="submit" value="Update Address">
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <?php
    $conn->close();
    ?>
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
<script src="../js/saved_addresses.js"></script>
<script src="../js/search.js"></script>
</body>
</html>
