<?php
session_start(); // ✅ MUST be first
include "../config/config.php";

// Safety check
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$uid = $_SESSION['user_id'];
$title = $_POST['title'];
$desc = $_POST['description'];

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

// Insert room
$stmt = $conn->prepare(
    "INSERT INTO rooms (user_id, title, description, image) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("isss", $uid, $title, $desc, $image);
$stmt->execute();

header("Location: ../pages/rooms.php");
exit();
?>