<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/admin.php?section=rooms");
    exit();
}

$room_id = (int)$_GET['id'];

// Delete feedback associated with the room first
$conn->query("DELETE FROM feedback WHERE room_id = $room_id");

// Delete the room
$result = $conn->query("DELETE FROM rooms WHERE room_id = $room_id");

if ($result) {
    header("Location: ../pages/admin.php?section=rooms&deleted=success");
} else {
    header("Location: ../pages/admin.php?section=rooms&error=failed");
}
exit();
?>
