<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_POST['feedback_id'], $_POST['message'], $_POST['display_name'])) {
    header("Location: ../pages/admin.php?section=feedback&error=invalid");
    exit();
}

$feedback_id = (int)$_POST['feedback_id'];
$message = trim($_POST['message']);
$display_name = trim($_POST['display_name']);

if (empty($message) || empty($display_name)) {
    header("Location: ../pages/admin.php?section=feedback&error=empty_fields");
    exit();
}

$stmt = $conn->prepare("UPDATE feedback SET message = ?, display_name = ? WHERE feedback_id = ?");
$stmt->bind_param("ssi", $message, $display_name, $feedback_id);
$result = $stmt->execute();

if ($result) {
    header("Location: ../pages/admin.php?section=feedback&updated=success");
} else {
    header("Location: ../pages/admin.php?section=feedback&error=update_failed");
}
exit();
?>