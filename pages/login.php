<?php
// Include database configuration file
include "../config/config.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Link to external CSS stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Login form that submits to login_user.php action handler -->
<form method="POST" action="../actions/login_user.php" class="login-form">
    <h2>Log In</h2>

    <!-- Email input field -->
    <input name="email" type="email" placeholder="Email" required>
    <!-- Password input field -->
    <input name="password" type="password" placeholder="Password" required>

    <!-- Submit button for login -->
    <button type="submit">Login</button>
    <!-- Link to registration page for new users -->
    <a href="register.php">No account? Register!</a>
</form>

</body>
</html>
