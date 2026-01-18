
<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5f3c0ac785.js" crossorigin="anonymous"></script>
</head>
<?php
// Include authentication checks and site header (should provide session and $conn)
include "../includes/auth.php";
include "../includes/header.php";

// Messages (optional)
if (isset($_GET['deleted'])) {
    echo '<div class="message message-success">Deleted successfully.</div>';
} elseif (isset($_GET['updated'])) {
    echo '<div class="message message-success">Updated successfully.</div>';
} elseif (isset($_GET['error'])) {
    $err = htmlspecialchars($_GET['error']);
    echo "<div class=\"message message-error\">Error: {$err}</div>";
}

// Validate room id
if (!isset($_GET['id'])) {
    die("Room ID missing");
}
$id = (int) $_GET['id'];

// Fetch room with owner info
$roomStmt = $conn->prepare(
    "SELECT r.room_id, r.title, r.description, r.image, r.user_id AS owner_id, u.username 
     FROM rooms r 
     INNER JOIN users u ON r.user_id = u.user_id 
     WHERE r.room_id = ?"
);
$roomStmt->bind_param("i", $id);
$roomStmt->execute();
$room = $roomStmt->get_result()->fetch_assoc();
$roomStmt->close();

if (!$room) {
    die("Room not found");
}

// Determine if current user can manage the room
$currentUserId = $_SESSION['user_id'] ?? null;
$currentRole = $_SESSION['role'] ?? '';
$canManageRoom = ($currentUserId !== null && $currentUserId === (int)$room['owner_id']) || $currentRole === 'admin';
?>

<div class="container">
    <h2 class="room-title"><?= htmlspecialchars($room['title']) ?></h2>
    <p class="room-desc"><?= nl2br(htmlspecialchars($room['description'])) ?></p>
    <?php if ($room['image']): ?>
        <img src="../assets/images/<?= htmlspecialchars($room['image']) ?>" alt="Room Image" style="max-width: 100%; max-height: 200px; height: auto; margin: 0 auto 20px auto; display: block;">
    <?php endif; ?>
    <p class="made-by">Room made by <?= htmlspecialchars($room['username']) ?></p>

    <?php if ($canManageRoom): ?>
        <div style="margin:10px 0;">
            <a href="edit_room.php?id=<?= $room['room_id'] ?>" class="btn-link" style="margin-right:12px;">Edit Room</a>
            <a href="../actions/delete_room.php?id=<?= $room['room_id'] ?>" class="btn-link" onclick="return confirm('Delete this room?');">Delete Room</a>
        </div>
    <?php endif; ?>

    <form method="POST" action="../actions/submit_feedback.php" enctype="multipart/form-data" class="feedback-form" style="margin-top:20px;">
        <input type="hidden" name="room_id" value="<?= $id ?>">

        <?php
        // Use session username as the default display name if available
        $sessionName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anon';
        ?>
        <input type="hidden" name="display_name" value="<?= htmlspecialchars($sessionName) ?>">

        <textarea name="message" placeholder="Write your feedback..." required></textarea>
        <input type="file" name="image" accept="image/*" class="file-input">

        <button type="submit">Post Feedback</button>
        <a href="rooms.php" class="link">Back to rooms</a>
    </form>

    <div class="comment-area" style="margin-top:30px;">
        <h2 style="color:#151515; text-align: center; margin-bottom:10px">Feedbacks</h2>
    <?php
    // Get all feedback for this specific room, newest first
    $comments = $conn->prepare(
        "SELECT feedback_id, user_id, display_name, message, created_at, image
         FROM feedback
         WHERE room_id = ?
         ORDER BY created_at DESC"
    );
    $comments->bind_param("i", $id);
    $comments->execute();
    $result = $comments->get_result();

    while ($row = $result->fetch_assoc()):
        $isOwner = (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$row['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
    ?>
        <div class="comment">
            <strong class="msg-title"><?= htmlspecialchars($row['display_name']) ?></strong>
            <p class="msg"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <?php if ($row['image']): ?>
                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="Feedback Image" style="max-width: 200px; height: auto; margin-top: 10px; display: block;">
            <?php endif; ?>
            <small class="crdt"><?= htmlspecialchars($row['created_at']) ?></small>

            <?php if ($isOwner): ?>
                <div style="margin-top:6px;">
                    <a href="edit_feedback.php?id=<?= $row['feedback_id'] ?>" style="margin-right:8px;">Edit</a>
                    <a href="../actions/delete_feedback.php?id=<?= $row['feedback_id'] ?>" onclick="return confirm('Delete this feedback?')">Delete</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile;
    $comments->close();
    ?>
    </div>
</div>
</body>
</html>