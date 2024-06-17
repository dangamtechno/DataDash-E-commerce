<?php 

require_once '../../backend/utils/session.php';
require_once '../../backend/vendor/autoload.php'; // Include Composer's autoload file for PHPMailer
//require_once '../../backend/utils/database_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function getUserFirstName() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $user_id = getSessionUserID();
        $query = "SELECT first_name FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $first_name = $row['first_name'];
        }
        $stmt->close();
        $conn->close();
        return $first_name ?? null;
    }
    return null;
}

function getUserLastName() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $user_id = getSessionUserID();
        $query = "SELECT last_name FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_name = $row['last_name'];
        }
        $stmt->close();
        $conn->close();
        return $last_name ?? null;
    }
    return null;
}


function getUserEmail() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $user_id = getSessionUserID();
        $query = "SELECT email FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['email'];
        }
        $stmt->close();
        $conn->close();
        return $email ?? null;
    }
    return null;
}

function getOrderID() {
    if (sessionExists()) {
        $conn = new mysqli("localhost", "root", "", "datadash");
        $user_id = getSessionUserID();
        $query = "SELECT order_id FROM orders WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $order_id = $row['order_id'];
        }
        $stmt->close();
        $conn->close();
        return $order_id ?? null;
    }
    return null;
}


if (getSessionUserID() && getOrderID()){
    
    if ($stmt = $mysqli->prepare("SELECT orders.order_id, orders.total_amount, orders.order_date 
        FROM orders
        WHERE orders.order_id = ?
    ")) {
        $stmt->bind_param("i", getOrderID());
        $stmt->execute();
        $result = $stmt->get_result();
        $orderDetails = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if ($orderDetails) {
            $email = getUserEmail();
            $firstName = getUserFirstName();
            sendConfirmationEmail($email, $firstName, $orderDetails);
        }
    } else {
        die("Error preparing statement: " . $mysqli->error);
    }

    $mysqli->close();
} else {
    $orderDetails = null;
}

// Function to send confirmation email
function sendConfirmationEmail($to, $firstName, $orderDetails) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '*******@gmail.com'; //Complete
        $mail->Password = '*******'; //Complete
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('datadashservices@gmail.com', 'Datadash'); // Replace with your name and email
        $mail->addAddress(getUserEmail(), getUserFirstName());

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation';
        $mail->Body = buildEmailBody($firstName, $orderDetails);

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to build the email body
function buildEmailBody($firstName, $orderDetails) {
    $body = "<h1>Thank you for your order, $firstName!</h1>";
    $body .= "<h2>Order Details</h2>";
    $body .= "<table>";
    $body .= "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

    $totalPrice = 0;
    foreach ($orderDetails as $detail) {
        $itemTotal = $detail['price'] * $detail['quantity'];
        $totalPrice += $itemTotal;
        $body .= "<tr>";
        $body .= "<td>{$detail['name']}</td>";
        $body .= "<td>\${$detail['price']}</td>";
        $body .= "<td>{$detail['quantity']}</td>";
        $body .= "<td>\${number_format($itemTotal, 2)}</td>";
        $body .= "</tr>";
    }

    $body .= "</table>";
    $body .= "<p>Total Price: \${number_format($totalPrice, 2)}</p>";
    return $body;
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
</head>

<body>
    <header>
        <div class="heading">
            <div class="left-heading">
                <div class="logo">
                    <a href="homepage.php">
                        <img id="logo" src="../images/misc/DataDash.png" alt="" />
                    </a>
                </div>
                <div class="search-bar">
                    <form class="search" action="http://localhost:8081/backend/utils/search_catalog.php" method="POST">
                        <label>
                            <input type="search" name="search" placeholder="search...">
                        </label>
                        <select name="criteria" aria-label="label for the select" id="drop-down">
                            <option>Product Name</option>
                        </select>
                        <input type="submit" name="submit-search">
                    </form>
                </div>
            </div>
            <div class="right-heading">
                <div class="login-status">
                    <?php if (sessionExists()) : ?>
                        <div class="hello-message">
                            <span>Hello, <?php echo getSessionUsername(); ?></span>
                        </div>
                        <div class="icons">
                            <a href="account.php"><i class="fas fa-user-check fa-2x"></i>Account</a>
                            <a href="cart.php"><i class="fas fa-shopping-cart fa-2x"></i>Cart</a>
                            <a href="../../backend/utils/logout.php"><i class="fas fa-sign-out-alt fa-2x"></i>Logout</a>
                        </div>
                    <?php else : ?>
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
        <div class="navigation"></div>
    </header>
    
    <main>
        <div class="confirmation-container">
            <?php if ($orderDetails) : ?>
                <h1>Thank you for your order, <?php echo getUserFirstName() ?>!</h1>
                <div class="order-details">
                    <h2>Order Details</h2>
                    <table>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                        <?php
                        $totalPrice = 0;
                        foreach ($orderDetails as $detail) :
                            $itemTotal = $detail['price'] * $detail['quantity'];
                            $totalPrice += $itemTotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detail['name']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($detail['price'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($itemTotal, 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <div class="total">
                        <p>Total Price: $<?php echo htmlspecialchars(number_format($totalPrice, 2)); ?></p>
                    </div>
                </div>
            <?php else : ?>
                <p>Order not found.</p>
            <?php endif; ?>
        </div>
    </main>
<footer>
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
    <script src="../js/global.js"></script>
    <script src="../js/login.js"></script>
</body>

</html>