<?php
include "../includes/auth.php";
include "../includes/header.php";
include "../config/config.php";

if (!isset($_GET['id'])) {
    header("Location: ../pages/rooms.php?error=missing_id");
    exit();
}
$room_id = (int)$_GET['id'];

// fetch room
$stmt = $conn->prepare("SELECT room_id, user_id, title, description FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$res = $stmt->get_result();
$room = $res->fetch_assoc();
$stmt->close();

if (!$room) {
    header("Location: ../pages/rooms.php?error=not_found");
    exit();
}

$current = $_SESSION['user_id'] ?? null;
if ($current !== (int)$room['user_id'] && ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../pages/rooms.php?error=forbidden");
    exit();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Room</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container-editor">
    <h2>Edit Room</h2>
    <form method="POST" action="../actions/update_room.php">
        <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['room_id']) ?>">
        <div>
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($room['title']) ?>" required>
        </div>
        <div>
            <label>Description</label>
            <textarea name="description" rows="6" required><?= htmlspecialchars($room['description']) ?></textarea>
        </div>
        <div style="margin-top:10px;">
            <button type="submit">Save</button>
            <a href="room.php?id=<?= $room['room_id'] ?>">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>