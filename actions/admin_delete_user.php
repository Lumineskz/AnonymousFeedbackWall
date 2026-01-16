<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/admin.php?section=users");
    exit();
}

$user_id = (int)$_GET['id'];

// Check if this is the current admin user
if ($_SESSION['user_id'] === $user_id) {
    header("Location: ../pages/admin.php?section=users&error=cannot_delete_self");
    exit();
}

// Delete user's feedback first
$conn->query("DELETE FROM feedback WHERE user_id = $user_id");

// Delete user's rooms and their feedback
$rooms = $conn->query("SELECT room_id FROM rooms WHERE user_id = $user_id");
while ($room = $rooms->fetch_assoc()) {
    $conn->query("DELETE FROM feedback WHERE room_id = " . $room['room_id']);
}

// Delete user's rooms
$conn->query("DELETE FROM rooms WHERE user_id = $user_id");

// Delete the user
$result = $conn->query("DELETE FROM users WHERE user_id = $user_id");

if ($result) {
    header("Location: ../pages/admin.php?section=users&deleted=success");
} else {
    header("Location: ../pages/admin.php?section=users&error=failed");
}
exit();
?>
