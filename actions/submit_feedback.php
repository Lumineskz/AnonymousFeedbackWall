<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php
// Start session to access user data
session_start();
// Include database configuration
include "../config/config.php";

// Security checks - verify user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

// Check if required form fields are submitted
if (!isset($_POST['room_id'], $_POST['message'])) {
    die("Invalid request");
}

// Get form data and clean it up
$room_id = (int) $_POST['room_id']; // Convert to integer for safety
$user_id = $_SESSION['user_id']; // Get logged-in user ID from session
$message = trim($_POST['message']); // Remove extra whitespace
$display_name = trim($_POST['display_name']); // Remove extra whitespace

// If user didn't provide a name, set it to "Anon"
if ($display_name === "") {
    $display_name = "Anon";
}

// Insert feedback into database using prepared statement (prevents SQL injection)
$stmt = $conn->prepare(
    "INSERT INTO feedback (room_id, user_id, message, display_name)
     VALUES (?, ?, ?, ?)"
);
// Bind parameters: i=integer, s=string
$stmt->bind_param("iiss", $room_id, $user_id, $message, $display_name);
$stmt->execute();

// Send user back to the room page after feedback is submitted
header("Location: ../pages/room.php?id=$room_id");
exit();
