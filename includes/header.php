<?php
include_once __DIR__ . "/../config/config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Anon Feedback Wall</title>
</head>
<body>
<div class="nav">
    <span><?= $_SESSION['username'] ?? 'Guest' ?></span>
    <div style="display: flex; gap: 15px;">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin.php" style="background-color: #e67e22; border-color: #d35400;">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
</div>
