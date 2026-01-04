<?php
// Include database configuration
include "../config/config.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include header template
include "../includes/header.php";
?>

<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php include "../includes/auth.php"; ?>
<form method="POST" action="../actions/create_room.php" class="create-form">
    <h2>Create Room</h2>
    <input name="title" placeholder="Room Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="file" name="image" class="file-input">
    <button>Create</button>
    <a href="rooms.php" class="link">Back to rooms</a>
</form>
