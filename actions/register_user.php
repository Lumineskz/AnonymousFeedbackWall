<?php
// Include database configuration
include "../config/config.php";

// Get form data
$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insert new user into the database
$stmt = $conn->prepare("INSERT INTO users(email, username, password) VALUES(?, ?, ?)");
$stmt->bind_param("sss", $email, $username, $password);
$stmt->execute();

// Redirect user to login page after successful registration
header("Location: ../pages/login.php");
?>