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
    <a href="logout.php">Logout</a>
</div>
