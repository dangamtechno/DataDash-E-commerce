<?php
require_once '../../backend/utils/session.php';

// Connect to database
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user_id from the sessions table
$sql1 = "SELECT user_id FROM users WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id)";
$result = $conn->query($sql1);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    // Get payment methods from database
    $sql2 = "SELECT * FROM payment_methods WHERE user_id = '$user_id'";
    $payment_methods_result = $conn->query($sql2);
} else {
    echo "User not found in the sessions table.";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_payment_method'])) {
        // Add new payment method
        $method_type = $_POST['method_type'];
        $card_number = $_POST['card_number'];
        $cvs_number = $_POST['cvs_number'];
        $expiration_date = $_POST['expiration_date'];

        $sql = "INSERT INTO payment_methods (user_id, method_type, card_number, cvs_number, expiration_date)
                VALUES ('$user_id', '$method_type', '$card_number', '$cvs_number', '$expiration_date')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>New payment method added successfully.</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['update_payment_method'])) {
        // Update existing payment method
        $payment_method_id = $_POST['payment_method_id'];
        $method_type = $_POST['method_type'];
        $card_number = $_POST['card_number'];
        $cvs_number = $_POST['cvs_number'];
        $expiration_date = $_POST['expiration_date'];

        $sql = "UPDATE payment_methods SET method_type = '$method_type', card_number = '$card_number',
                cvs_number = '$cvs_number', expiration_date = '$expiration_date' WHERE payment_method_id = '$payment_method_id' AND user_id = '$user_id'";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='success-message'>Payment method updated successfully.</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


// Delete payment method
if (isset($_GET['delete_id'])) {
    $payment_method_id = $_GET['delete_id'];

    $sql = "DELETE FROM payment_methods WHERE payment_method_id = '$payment_method_id' AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='success-message'>Payment method deleted successfully.</div>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style>
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
            background-color: #4CAF50;
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
        .no-payment-methods {
            text-align: center;
            font-style: italic;
            color: #888;
            margin-bottom: 20px;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .social-media ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .social-media li {
            display: inline-block;
            margin-right: 10px;
        }

        .social-media i {
            margin-right: 5px;
        }

        .general-info {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .general-info div {
            text-align: left;
        }

        .general-info h3 {
            margin-bottom: 10px;
        }

        .general-info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .general-info li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payment Methods</h2>

        <?php if ($payment_methods_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Payment Method ID</th>
                    <th>Method Type</th>
                    <th>Card Number</th>
                    <th>CVS Number</th>
                    <th>Expiration Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $payment_methods_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['payment_method_id']; ?></td>
                        <td><?php echo $row['method_type']; ?></td>
                        <td><?php echo $row['card_number']; ?></td>
                        <td><?php echo $row['cvs_number']; ?></td>
                        <td><?php echo $row['expiration_date']; ?></td>
                        <td>
                            <a href="#" onclick="showEditForm(<?php echo $row['payment_method_id']; ?>, '<?php echo $row['method_type']; ?>', '<?php echo $row['card_number']; ?>', '<?php echo $row['cvs_number']; ?>', '<?php echo $row['expiration_date']; ?>'); return false;"><i class="fas fa-edit"></i> Edit</a> |
                            <a href="?delete_id=<?php echo $row['payment_method_id']; ?>" onclick="return confirm('Are you sure you want to delete this payment method?')"><i class="fas fa-trash-alt"></i> Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-payment-methods">You have no saved payment methods.</p>
        <?php endif; ?>

        <!-- Add New Payment Method Form -->
        <h3>Add New Payment Method</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="add-payment-method-form">
            <input type="hidden" name="add_payment_method" value="1">
            <label for="method_type">Method Type (Credit Card, PayPal, etc.):</label>
            <input type="text" name="method_type" required>

            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" required>

            <label for="cvs_number">CVS Number:</label>
            <input type="text" name="cvs_number" required>

            <label for="expiration_date">Expiration Date:</label>
            <input type="date" name="expiration_date" required>

            <input type="submit" value="Add Payment Method">
        </form>

        <!-- Edit Payment Method Form -->
        <div id="edit-payment-method-form" style="display: none;">
            <h3>Edit Payment Method</h3>
            <form id="edit-payment-method-popup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="update_payment_method" value="1">
                <input type="hidden" name="payment_method_id" id="edit-payment-method-id">
                <label for="edit-method-type">Method Type (Credit Card, PayPal, etc.):</label>
                <input type="text" name="method_type" id="edit-method-type" required>

                <label for="edit-card-number">Card Number:</label>
                <input type="text" name="card_number" id="edit-card-number" required>

                <label for="edit-cvs-number">CVS Number:</label>
                <input type="text" name="cvs_number" id="edit-cvs-number" required>

                <label for="edit-expiration-date">Expiration Date:</label>
                <input type="date" name="expiration_date" id="edit-expiration-date" required>

                <input type="submit" value="Update Payment Method">
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>

    <?php
// Close database connection
$conn->close();
?>

 <footer>
        <a href="account.php">
            <button style="background-color: #680eea; color: #fff; padding: 10px 20px; border: none; border-radius: 5px;
             cursor: pointer;">Back to Account</button>
        </a>
        <div class="social-media">
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
    <script src="../js/payment_methods.js"></script>
</body>
</html>

