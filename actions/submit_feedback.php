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

$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }
}

// Insert feedback into database using prepared statement (prevents SQL injection)
$stmt = $conn->prepare(
    "INSERT INTO feedback (room_id, user_id, message, display_name, image)
     VALUES (?, ?, ?, ?, ?)"
);
// Bind parameters: i=integer, s=string
$stmt->bind_param("iisss", $room_id, $user_id, $message, $display_name, $image);
$stmt->execute();

// Send user back to the room page after feedback is submitted
header("Location: ../pages/room.php?id=$room_id");
exit();
