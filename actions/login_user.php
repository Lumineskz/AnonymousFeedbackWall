<?php
// Start session to store user information
session_start();
// Include database configuration
include "../config/config.php";

if (!isset($_POST['email'], $_POST['password'])) {
    die("Invalid request");
}

$e = trim($_POST['email']);
$p = $_POST['password'];

// Use prepared statement to safely query the database
$stmt = $conn->prepare(
    "SELECT user_id, username, password, role
     FROM users
     WHERE email = ?"
);
// Bind the email variable to the ? placeholder
$stmt->bind_param("s", $e);
// Execute the prepared statement
$stmt->execute();
$result = $stmt->get_result();
// Fetch the user data as an associative array
$user = $result->fetch_assoc();

// Check if user exists and verify the password hash
if ($user && password_verify($p, $user['password'])) {
    // Store user information in session for later use
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Redirect to rooms page after successful login
    header("Location: ../pages/rooms.php");
    exit();
} else {
    // Login failed - user not found or password incorrect
    echo "Invalid username or password";
}
// Close the statement and database connection
$stmt->close();
$conn->close();
?>