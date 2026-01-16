<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_POST['room_id'], $_POST['title'], $_POST['description'])) {
    header("Location: ../pages/admin.php?section=rooms&error=invalid");
    exit();
}

$room_id = (int)$_POST['room_id'];
$title = trim($_POST['title']);
$description = trim($_POST['description']);

if (empty($title) || empty($description)) {
    header("Location: ../pages/admin.php?section=rooms&error=empty_fields");
    exit();
}

$stmt = $conn->prepare("UPDATE rooms SET title = ?, description = ? WHERE room_id = ?");
$stmt->bind_param("ssi", $title, $description, $room_id);
$result = $stmt->execute();

if ($result) {
    header("Location: ../pages/admin.php?section=rooms&updated=success");
} else {
    header("Location: ../pages/admin.php?section=rooms&error=update_failed");
}
exit();
?>
