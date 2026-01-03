<?php
session_start();
include "../config/config.php";

if (!isset($_POST['username'], $_POST['password'])) {
    die("Invalid request");
}

$u = trim($_POST['username']);
$p = $_POST['password'];

// Use prepared statement
$stmt = $conn->prepare(
    "SELECT user_id, username, password, role
     FROM users
     WHERE username = ?"
);
$stmt->bind_param("s", $u);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($p, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: ../pages/rooms.php");
    exit();
} else {
    echo "Invalid username or password";
}
$stmt->close();
$conn->close();
?>