<?php
session_start(); // ✅ MUST be first
include "../config/config.php";

// Safety check
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$uid = $_SESSION['user_id'];
$title = $_POST['title'];
$desc = $_POST['description'];

// Insert room
$stmt = $conn->prepare(
    "INSERT INTO rooms (user_id, title, description) VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $uid, $title, $desc);
$stmt->execute();

header("Location: ../pages/rooms.php");
exit();
?>