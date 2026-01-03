<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php
include "../includes/auth.php";
include "../includes/header.php";   // <-- provides $conn

// 1️⃣ Validate room ID
if (!isset($_GET['id'])) {
    die("Room ID missing");
}

$id = (int) $_GET['id'];

// 2️⃣ Fetch room info FIRST
$roomStmt = $conn->prepare(
    "SELECT title, description FROM rooms WHERE room_id = ?"
);
$roomStmt->bind_param("i", $id);
$roomStmt->execute();
$room = $roomStmt->get_result()->fetch_assoc();

if (!$room) {
    die("Room not found");
}
?>

<!-- 3️⃣ Display room -->
<h2><?= htmlspecialchars($room['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($room['description'])) ?></p>


<!-- 4️⃣ Comment form -->
<form method="POST" action="../actions/submit_feedback.php" class="feedback-form">
    <input type="hidden" name="room_id" value="<?= $id ?>">

    <?php
    $sessionName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anon';
    ?>
    <input type="hidden" name="display_name" id="display_name_input" value="Anon">
    <label>
        <input type="checkbox" id="use_session_name_chk" name="use_session_name" value="1">
        <p class="uname">Use my account name (<?= htmlspecialchars($sessionName) ?>)</p>
    </label>
    <script>
    (function(){ // IIFE to avoid global scope pollution
        var chk = document.getElementById('use_session_name_chk');
        var inp = document.getElementById('display_name_input');
        var uname = <?= json_encode($sessionName) ?>;
        chk.addEventListener('change', function(){
            inp.value = this.checked ? uname : 'Anon';
        });
    })();
    </script>

    <textarea name="message"
              placeholder="Write your feedback..."
              required></textarea>

    <button type="submit">Post Feedback</button>
    <a href="rooms.php" class="link">Back to rooms</a>
</form>


<!-- 5️⃣ Fetch & display comments -->
 <div class="comment-area">
    <h2 style="color:white; text-align: center; margin-bottom:10px">Feedbacks</h2>
<?php
$comments = $conn->prepare(
    "SELECT display_name, message, created_at
     FROM feedback
     WHERE room_id = ?
     ORDER BY created_at DESC"
);
$comments->bind_param("i", $id);
$comments->execute();
$result = $comments->get_result();

while ($row = $result->fetch_assoc()):
?>
    <div class="comment">
        <strong class="msg-title"><?= htmlspecialchars($row['display_name']) ?></strong>
        <p class="msg"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <small class="crdt"><?= $row['created_at'] ?></small>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>
