<?php
// Update feedback by its author (or admin)
include "../config/config.php";
include "../includes/auth.php";

if (!isset($_POST['feedback_id'], $_POST['message'], $_POST['display_name'])) {
    header("Location: ../pages/rooms.php?error=invalid");
    exit();
}

$feedback_id = (int)$_POST['feedback_id'];
$message = trim($_POST['message']);
$display_name = trim($_POST['display_name']);

if ($message === '' || $display_name === '') {
    header("Location: ../pages/edit_feedback.php?id={$feedback_id}&error=empty");
    exit();
}

// Get feedback owner and room id
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

// Update
$stmt = $conn->prepare("UPDATE feedback SET message = ?, display_name = ? WHERE feedback_id = ?");
$stmt->bind_param("ssi", $message, $display_name, $feedback_id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    header("Location: ../pages/room.php?id={$room_id}&updated=success");
} else {
    header("Location: ../pages/edit_feedback.php?id={$feedback_id}&error=update_failed");
}
exit();
?>