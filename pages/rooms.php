<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://kit.fontawesome.com/5f3c0ac785.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
include "../includes/auth.php";
include "../includes/header.php";

$result = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");

?>

<div class="container">
    <div class="room-container">
    <h2 style="color:white; text-align:center;">Rooms</h2>
    

<form action="../actions/search.php" method="post" class="search-form">
    <a href="create_room.php" class="create-room"><i class="fa-solid fa-plus"></i> Create Room</a>
    
    <input type="text" name="search_term" id="search" placeholder="Search rooms...">
    
    <button type="submit" name="submit_search"><i class="fa-solid fa-magnifying-glass"></i></button>
    <button type="submit" name="clear_search">Clear</button>
</form>

<div class="rooms">
<?php while ($room = $result->fetch_assoc()): ?>
    <div class="card-room">
        <h4>
            <a href="room.php?id=<?= $room['room_id'] ?>">
                <?= htmlspecialchars($room['title']) ?>
            </a>
        </h4>
        <p><?= htmlspecialchars($room['description']) ?></p>
    </div>
<?php endwhile; ?>
</div>
    </div>
</div>
</body>
</html>
