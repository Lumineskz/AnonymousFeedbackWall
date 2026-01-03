<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php
session_start();
include "../config/config.php";

// Security checks
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if (!isset($_POST['room_id'], $_POST['message'])) {
    die("Invalid request");
}

$room_id = (int) $_POST['room_id'];
$user_id = $_SESSION['user_id'];
$message = trim($_POST['message']);
$display_name = trim($_POST['display_name']);

if ($display_name === "") {
    $display_name = "Anon";
}

// Insert feedback
$stmt = $conn->prepare(
    "INSERT INTO feedback (room_id, user_id, message, display_name)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("iiss", $room_id, $user_id, $message, $display_name);
$stmt->execute();

// Redirect back to room
header("Location: ../pages/room.php?id=$room_id");
exit();
