<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = ""; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  // Hash the password
  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  // Insert user into database
  $sql = "INSERT INTO Users (first_name, last_name, username, email, password_hash, registration_date)
          VALUES ('$first_name', '$last_name', '$username', '$email', '$password_hash', NOW())";

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataDash - Home</title>
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color: #E1E8EE;">
    <!-- Nav bar -->
    <nav class="top-nav">
        <div class="left">
          <a href="#">SHOP</a>
          <a href="#">ABOUT</a>
          <a href="#">CONTACT</a>
        </div>
        <div class="middle">
          <a href="index.html"><img src="image/DataDashLogoSlogan.png" alt="DataDash Logo"></a>
        </div>
        <div class="right">
          <a href="index.html" class="black">Sign Up/Login</a>
          <div class="cart">
            <img src="image/ShoppingCartLogo.png" alt="Cart">
            <span class="cart-items">0</span>
          </div>
        </div>
    </nav>
  
    <!-- Product grid -->
    <div class="product-grid">
        <div class="product">
            <img src="images/product1.jpg" alt="Product 1">
            <div class="product-details">
                <h3>Product Name 1</h3>
                <p>$19.99</p>
            </div>
        </div>
        <div class="product">
            <img src="images/product2.jpg" alt="Product 2">
            <div class="product-details">
                <h3>Product Name 2</h3>
                <p>$29.99</p>
            </div>
        </div>
        <div class="product">
            <img src="images/product3.jpg" alt="Product 3">
            <div class="product-details">
                <h3>Product Name 3</h3>
                <p>$24.99</p>
            </div>
        </div>
        <div class="product">
            <img src="images/product4.jpg" alt="Product 4">
            <div class="product-details">
                <h3>Product Name 4</h3>
                <p>$14.99</p>
            </div>
        </div>
    </div>
</body>
</html>
