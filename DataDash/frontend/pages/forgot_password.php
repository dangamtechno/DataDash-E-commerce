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
    $favorite_movie = $_POST["favorite_movie"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Get the user's favorite movie from the database
    $sql = "SELECT favorite_movie FROM users WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id);";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_favorite_movie = $row["favorite_movie"];

        // Check if the favorite movie matches (case-insensitive)
        if (strtolower($favorite_movie) === strtolower($user_favorite_movie)) {
            // Check if the new password and confirm password match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $sql = "UPDATE users SET password_hash='$new_password_hash' WHERE user_id = (SELECT user_id FROM sessions WHERE user_id = users.user_id);";

                if ($conn->query($sql) === TRUE) {
                    echo "<p class='success-message'>Password reset successful!</p>";
                } else {
                    echo "<p class='error-message'>Error updating password: " . $conn->error . "</p>";
                }
            } else {
                echo "<p class='error-message'>New password and confirm password do not match.</p>";
            }
        } else {
            echo "<p class='error-message'>Favorite movie is incorrect.</p>";
        }
    } else {
        echo "<p class='error-message'>Error retrieving user data.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
        .success-message {
            color: #4CAF50;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .error-message {
            color: #FF0000;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <div class="form-container">
            <form method="post" id="forgot-password-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="favorite_movie">Favorite Movie</label>
                <input type="text" id="favorite_movie" name="favorite_movie" required>

                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>

<script>
  const form = document.getElementById('forgot-password-form');
  const favoriteMovieInput = document.getElementById('favorite_movie');
  const passwordInput = document.getElementById('new_password');
  const confirmPasswordInput = document.getElementById('confirm_password');
  const passwordMismatchError = document.getElementById('password-mismatch-error');

  form.addEventListener('submit', function(event) {
    if (passwordInput.value !== confirmPasswordInput.value) {
      event.preventDefault(); // Prevent form submission
      passwordMismatchError.style.display = 'block'; // Show error message
    } else {
      passwordMismatchError.style.display = 'none'; // Hide error message
    }
  });
</script>

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
</body>
</html>