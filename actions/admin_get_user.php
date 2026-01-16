<?php
session_start();
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID required']);
    exit();
}

$user_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT user_id, username, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit();
}

header('Content-Type: application/json');
echo json_encode($user);
?>
