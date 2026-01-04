<?php
// Include database configuration
include "../config/config.php";

// Get username from form submission
$u = $_POST['username'];

// Hash the password for secure storage
$p = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insert new user into the database
$conn->query("INSERT INTO users(username,password) VALUES('$u','$p')");

// Redirect user to login page after successful registration
header("Location: ../pages/login.php");
?>