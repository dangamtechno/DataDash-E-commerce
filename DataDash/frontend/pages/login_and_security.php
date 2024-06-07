<?php
// Create connection
$conn = new mysqli("localhost", "root", "", "datadash");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $new_username = $_POST["username"];
    $new_first_name = $_POST["first_name"];
    $new_last_name = $_POST["last_name"];
    $new_favorite_movie = $_POST["favorite_movie"];
    $new_phone = $_POST["phone"];

    // Update user data in the database
    $sql = "UPDATE users SET username='$new_username', first_name='$new_first_name', last_name='$new_last_name',
                  favorite_movie='$new_favorite_movie', phone='$new_phone'  WHERE user_id = 
                    (SELECT user_id FROM sessions WHERE user_id = users.user_id);";

    if ($conn->query($sql) === TRUE) {
        // Redirect to account.php after successful update
        header("Location: login_and_security.php");
        exit();
    } else {
        echo "Error updating user data: " . $conn->error;
    }
}

// Get user data from the database
$sql = "SELECT username, first_name, last_name, password_hash, favorite_movie, phone FROM users 
            WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id);";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["username"];
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $favorite_movie = $row["favorite_movie"];
    $phone = $row["phone"];
} else {
    echo "No user data found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Login and Security</title>
        <link rel="stylesheet" href="../css/style.css">

<style>

body {
font-family: Arial, sans-serif;
background-color: #f2f2f2;
margin: 0;
padding: 0;
}
.container {
max-width: 400px;
margin: 50px auto;
background-color: #fff;
padding: 20px;
border-radius: 5px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
h2 {
text-align: center;
color: #333;
}
input[type=text], input[type=password] {
width: 100%;
padding: 12px 20px;
margin: 8px 0;
display: inline-block;
border: 1px solid #ccc;
border-radius: 4px;
box-sizing: border-box;
}
button {
background-color: #4CAF50;
color: white;
padding: 14px 20px;
margin: 8px 0;
border: none;
border-radius: 4px;
cursor: pointer;
width: 100%;
}
button:hover {
background-color: #45a049;
}
</style>
</head>
<body>
<div class="container">
<h2>Login and Security</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<label for="username">Username</label>
<input type="text" id="username" name="username" value="<?php echo $username; ?>" required>

<label for="firstname">First Name</label>
<input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>

<label for="lastname">Last Name</label>
<input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>

<label for="favmovie">Favorite Movie</label>
<input type="text" id="favorite_movie" name="favorite_movie" value="<?php echo $favorite_movie; ?>" required>

<label for="phone">Phone Number</label>
<input type="text" id="phone" name="phone" value="<?php echo $phone; ?>">

<button type="submit">Update</button>

<br><br><br>
<a href="reset_password.php">
    <button type="button" style="background-color: blue; color: white;">Reset Password</button>
</a>

</form>
</div>
</body>
<footer>
    <a href="account.php">
    <button style="background-color: #4218d9; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Back to Account</button>
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
</html>