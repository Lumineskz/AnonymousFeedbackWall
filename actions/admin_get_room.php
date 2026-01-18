<?php
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Room ID required']);
    exit();
}

$room_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT room_id, title, description FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    http_response_code(404);
    echo json_encode(['error' => 'Room not found']);
    exit();
}

header('Content-Type: application/json');
echo json_encode($room);
?>