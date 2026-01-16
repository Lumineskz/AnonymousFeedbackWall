<?php
include_once __DIR__ . "/../config/config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Anon Feedback Wall</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5f3c0ac785.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="admin-nav">
    <div class="nav-brand">
        <h3>Admin Panel</h3>
    </div>
    <div class="nav-links">
        <span class="admin-user"><?= $_SESSION['username'] ?? 'Admin' ?></span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="admin-container">
    <aside class="admin-sidebar">
        <nav class="admin-nav-menu">
            <a href="admin.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
            <a href="admin.php?section=rooms" class="nav-item <?= (isset($_GET['section']) && $_GET['section'] == 'rooms') ? 'active' : '' ?>">
                <i class="fa-solid fa-door-open"></i> Manage Rooms
            </a>
            <a href="admin.php?section=users" class="nav-item <?= (isset($_GET['section']) && $_GET['section'] == 'users') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Manage Users
            </a>
            <a href="admin.php?section=feedback" class="nav-item <?= (isset($_GET['section']) && $_GET['section'] == 'feedback') ? 'active' : '' ?>">
                <i class="fa-solid fa-comments"></i> Manage Feedback
            </a>
        </nav>
    </aside>

    <main class="admin-main">
