<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rooms</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5f3c0ac785.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
// Authentication and header (starts session and provides $conn)
include "../includes/auth.php";
include "../includes/header.php";

// Optional messages
if (isset($_GET['deleted'])) {
    echo '<div class="message message-success">Deleted successfully.</div>';
} elseif (isset($_GET['error'])) {
    echo '<div class="message message-error">Error: ' . htmlspecialchars($_GET['error']) . '</div>';
}

// Prepare result set based on search or default listing
$search_term = "";

if (isset($_POST['submit_search']) && !empty($_POST['search_term'])) {
    $search_term = $conn->real_escape_string($_POST['search_term']);
    $sql = "SELECT * FROM rooms WHERE title LIKE '%$search_term%' OR description LIKE '%$search_term%' ORDER BY created_at DESC";
    $result = $conn->query($sql);
} elseif (isset($_POST['clear_search'])) {
    $result = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");
} else {
    $result = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");
}

if ($result === false) {
    die("Database error: " . $conn->error);
}
?>

<div class="container">
    <div class="room-container">
        <h2 style="color:black; text-align:center;">Rooms</h2>
        
        <form action="" method="post" class="search-form">
            <a href="create_room.php" class="create-room"><i class="fa-solid fa-plus"></i> Create Room</a>
            
            <input type="text" name="search_term" id="search" placeholder="Search rooms..." value="<?= htmlspecialchars($search_term) ?>">
            
            <button type="submit" name="submit_search"><i class="fa-solid fa-magnifying-glass"></i></button>
            <button type="submit" name="clear_search">Clear</button>
        </form>

        <div class="rooms">
        <?php 
        // Loop through rooms and show owner controls when appropriate
        while ($room = $result->fetch_assoc()): 
            $isOwner = (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$room['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
        ?>
            <div class="card-room">
                <h4>
                    <a href="room.php?id=<?= $room['room_id'] ?>">
                        <?= htmlspecialchars($room['title']) ?>
                    </a>
                </h4>
                <p><?= htmlspecialchars($room['description']) ?></p>
                <?php if (!empty($room['image'])): ?>
                    <p style="color: #5a5de6; font-size: 14px; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <i class="fa-solid fa-image"></i> Includes an image
                    </p>
                <?php endif; ?>

                <?php if ($isOwner): ?>
                    <div style="margin-top:8px;">
                        <a href="edit_room.php?id=<?= $room['room_id'] ?>" style="margin-right:10px;">Edit</a>
                        <a href="../actions/delete_room.php?id=<?= $room['room_id'] ?>" onclick="return confirm('Delete this room?')">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; 
        $result->free();
        ?>
        </div>
    </div>
</div>
</body>
</html>