<?php
include "../includes/auth.php";
include "../includes/header.php";
include "../config/config.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/rooms.php?error=missing_id");
    exit();
}
$feedback_id = (int)$_GET['id'];

// fetch feedback
$stmt = $conn->prepare("SELECT feedback_id, user_id, room_id, display_name, message FROM feedback WHERE feedback_id = ?");
$stmt->bind_param("i", $feedback_id);
$stmt->execute();
$res = $stmt->get_result();
$fb = $res->fetch_assoc();
$stmt->close();

if (!$fb) {
    header("Location: ../pages/rooms.php?error=not_found");
    exit();
}

$current = $_SESSION['user_id'] ?? null;
if ($current !== (int)$fb['user_id'] && ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../pages/room.php?id={$fb['room_id']}&error=forbidden");
    exit();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container-editor">
    <h2>Edit Feedback</h2>
    <form method="POST" action="../actions/update_feedback.php">
        <input type="hidden" name="feedback_id" value="<?= htmlspecialchars($fb['feedback_id']) ?>">
        <div>
            <label>Display Name</label>
            <input type="text" name="display_name" value="<?= htmlspecialchars($fb['display_name']) ?>" required>
        </div>
        <div>
            <label>Message</label>
            <textarea name="message" rows="6" required><?= htmlspecialchars($fb['message']) ?></textarea>
        </div>
        <div style="margin-top:10px;">
            <button type="submit">Save</button>
            <a href="room.php?id=<?= $fb['room_id'] ?>">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>