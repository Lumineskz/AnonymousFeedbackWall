<?php
// Allow owners or admins to delete a feedback entry
include "../config/config.php";
include "../includes/auth.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/rooms.php?error=missing_id");
    exit();
}

$feedback_id = (int)$_GET['id'];

// Get feedback owner and room id for redirect
$stmt = $conn->prepare("SELECT user_id, room_id FROM feedback WHERE feedback_id = ?");
$stmt->bind_param("i", $feedback_id);
$stmt->execute();
$res = $stmt->get_result();
$fb = $res->fetch_assoc();
$stmt->close();

if (!$fb) {
    header("Location: ../pages/rooms.php?error=not_found");
    exit();
}

$owner_id = (int)$fb['user_id'];
$room_id = (int)$fb['room_id'];
$current = $_SESSION['user_id'] ?? null;

if ($current !== $owner_id && ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../pages/room.php?id={$room_id}&error=forbidden");
    exit();
}

$stmt = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
$stmt->bind_param("i", $feedback_id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    header("Location: ../pages/room.php?id={$room_id}&deleted=success");
} else {
    header("Location: ../pages/room.php?id={$room_id}&error=delete_failed");
}
exit();
?>