<?php
include "../config/config.php";
include "../includes/admin-auth.php";

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Feedback ID required']);
    exit();
}

$feedback_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT f.feedback_id, f.message, f.display_name, f.created_at, r.title FROM feedback f INNER JOIN rooms r ON f.room_id = r.room_id WHERE f.feedback_id = ?");
$stmt->bind_param("i", $feedback_id);
$stmt->execute();
$feedback = $stmt->get_result()->fetch_assoc();

if (!$feedback) {
    http_response_code(404);
    echo json_encode(['error' => 'Feedback not found']);
    exit();
}

header('Content-Type: application/json');
echo json_encode($feedback);
?>