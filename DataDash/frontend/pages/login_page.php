<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <?php require_once '../../backend/utils/session.php'; ?>

</head>
<body>
    <div class="topnav">
    <a href="homepage.php">Home</a>
    <?php if (sessionExists()): ?>
        <a href="../../backend/utils/logout.php">Logout</a>
    <?php else: ?>
        <a href="login_page.php">Login</a>
        <a href="create_account.php">Create Account</a>
    <?php endif; ?>
    <?php if (sessionExists()): ?>
        <a href="cart.php">Shopping Cart</a>
    <?php endif; ?>
</div>

    <div class="create-account-container login-container">
        <h1>User Login</h1>
        <p>Input your user info and click Submit.</p>

        <form action="../../backend/utils/login.php" method="post">
            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" required style="width: 100%; padding: 10px; margin-bottom: 20px;
             border: 1px solid #ccc;"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Sign in">
        </form>
        <p>Or</p>
        <p>Click here to create an account:</p>
        <a href="create_account.php">
            <button class="create-account">Create Account</button>
        </a>
    </div>
</body>
</html>
