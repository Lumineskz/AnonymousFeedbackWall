<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/admin.php?section=feedback");
    exit();
}

$feedback_id = (int)$_GET['id'];

// Delete the feedback
$result = $conn->query("DELETE FROM feedback WHERE feedback_id = $feedback_id");

if ($result) {
    header("Location: ../pages/admin.php?section=feedback&deleted=success");
} else {
    header("Location: ../pages/admin.php?section=feedback&error=failed");
}
exit();
?>
