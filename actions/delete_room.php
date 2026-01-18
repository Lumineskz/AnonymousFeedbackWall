<?php
// Allow owners or admins to delete a room
include "../config/config.php";
include "../includes/auth.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/rooms.php?error=missing_id");
    exit();
}

$room_id = (int)$_GET['id'];

// Get room owner
$stmt = $conn->prepare("SELECT user_id FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$res = $stmt->get_result();
$room = $res->fetch_assoc();
$stmt->close();

if (!$room) {
    header("Location: ../pages/rooms.php?error=not_found");
    exit();
}

$owner_id = (int)$room['user_id'];
$current = $_SESSION['user_id'] ?? null;

// Only owner or admin can delete
if ($current !== $owner_id && ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../pages/rooms.php?error=forbidden");
    exit();
}

// Delete room (feedback will cascade if FK is configured)
$stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    header("Location: ../pages/rooms.php?deleted=success");
} else {
    header("Location: ../pages/rooms.php?error=delete_failed");
}
exit();
?>