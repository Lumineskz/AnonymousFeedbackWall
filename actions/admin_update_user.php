<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_POST['user_id'], $_POST['role'])) {
    header("Location: ../pages/admin.php?section=users&error=invalid");
    exit();
}

$user_id = (int)$_POST['user_id'];
$role = $_POST['role'] === 'admin' ? 'admin' : 'user';

// Prevent self-demotion
if ($_SESSION['user_id'] === $user_id && $role !== $_SESSION['role']) {
    if ($role === 'user') {
        header("Location: ../pages/admin.php?section=users&error=cannot_demote_self");
        exit();
    }
}

$stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
$stmt->bind_param("si", $role, $user_id);
$result = $stmt->execute();

if ($result) {
    header("Location: ../pages/admin.php?section=users&updated=success");
} else {
    header("Location: ../pages/admin.php?section=users&error=update_failed");
}
exit();
?>
