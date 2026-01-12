<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
// Include authentication checks and database connection ($conn)
include "../includes/auth.php";
include "../includes/header.php"; 

// 1️⃣ VALIDATE ROOM ID
// Check if 'id' is present in the URL (e.g., room_feedback.php?id=5)
if (!isset($_GET['id'])) {
    die("Room ID missing");
}

// Cast to integer to prevent basic SQL injection attempts
$id = (int) $_GET['id'];

// 2️⃣ FETCH ROOM INFO WITH INNER JOIN
// We join 'rooms' (r) and 'users' (u) where their user_id matches.
// This allows us to get the room details AND the name of the person who created it.
$roomStmt = $conn->prepare(
    "SELECT r.title, r.description, r.image, u.username 
     FROM rooms r 
     INNER JOIN users u ON r.user_id = u.user_id 
     WHERE r.room_id = ?"
);
$roomStmt->bind_param("i", $id);
$roomStmt->execute();
$room = $roomStmt->get_result()->fetch_assoc();

// If no room matches that ID, stop the script
if (!$room) {
    die("Room not found");
}
?>

<div class="container">
    <h2 class="room-title"><?= htmlspecialchars($room['title']) ?></h2>
    <p class="room-desc"><?= nl2br(htmlspecialchars($room['description'])) ?></p>
    <?php if ($room['image']): ?>
        <img src="../assets/images/<?= htmlspecialchars($room['image']) ?>" alt="Room Image" style="max-width: 100%; max-height: 200px; height: auto; margin: 0 auto 20px auto; display: block;">
    <?php endif; ?>
    <p class="made-by">Room made by <?= htmlspecialchars($room['username']) ?></p>


    <form method="POST" action="../actions/submit_feedback.php" enctype="multipart/form-data" class="feedback-form">
        <input type="hidden" name="room_id" value="<?= $id ?>">

        <?php
        // Determine the name to show if the user chooses to use their account name
        $sessionName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anon';
        ?>
        
        <input type="hidden" name="display_name" value="<?= htmlspecialchars($sessionName) ?>">

        <textarea name="message" placeholder="Write your feedback..." required></textarea>
        <input type="file" name="image" accept="image/*" class="file-input">

        <button type="submit">Post Feedback</button>
        <a href="rooms.php" class="link">Back to rooms</a>
    </form>


    <div class="comment-area">
        <h2 style="color:#151515; text-align: center; margin-bottom:10px">Feedbacks</h2>
    <?php
    // Get all feedback for this specific room, newest first
    $comments = $conn->prepare(
        "SELECT display_name, message, created_at, image
         FROM feedback
         WHERE room_id = ?
         ORDER BY created_at DESC"
    );
    $comments->bind_param("i", $id);
    $comments->execute();
    $result = $comments->get_result();

    // Loop through every row returned by the database
    while ($row = $result->fetch_assoc()):
    ?>
        <div class="comment">
            <strong class="msg-title"><?= htmlspecialchars($row['display_name']) ?></strong>
            <p class="msg"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <?php if ($row['image']): ?>
                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="Feedback Image" style="max-width: 200px; height: auto; margin-top: 10px; display: block;">
            <?php endif; ?>
            <small class="crdt"><?= $row['created_at'] ?></small>
        </div>
    <?php endwhile; ?>
    </div>
</div>
</body>
</html>