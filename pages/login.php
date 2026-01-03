<?php
include "../config/config.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<form method="POST" action="../actions/login_user.php" class="login-form">
    <h2>Log In</h2>

    <input name="username" placeholder="Username" required>
    <input name="password" type="password" placeholder="Password" required>

    <button type="submit">Login</button>
    <a href="register.php">No account? Register!</a>
</form>

</body>
</html>
