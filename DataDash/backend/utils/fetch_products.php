<?php

require_once '../../backend/utils/session.php';
require_once '../../backend/utils/database.php'; 
require 'vendor/autoload.php'; // Include Composer's autoload file for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the user is logged in
if (!sessionExists()) {
    header('Location: login_page.php');
    exit;
}

$orderID = $_GET['order_id'] ?? null;

if ($orderID) {
    if ($stmt = $mysqli->prepare("
        SELECT orders.order_id, orders.total_amount, orders.order_date, 
               product.name, product.price, order_details.quantity,
               users.email, users.first_name
        FROM orders
        JOIN order_details ON orders.order_id = order_details.order_id
        JOIN product ON order_details.product_id = product.product_id
        JOIN users ON orders.user_id = users.user_id
        WHERE orders.order_id = ?
    ")) {
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $orderDetails = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if ($orderDetails) {
            $email = $orderDetails[0]['email'];
            $firstName = $orderDetails[0]['first_name'];
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
        $mail->setFrom('your-email@example.com', 'Your Name'); // Replace with your name and email
        $mail->addAddress($to, $firstName);

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

include 'confirmation_page.php';
