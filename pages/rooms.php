<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5f3c0ac785.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
// Include authentication check (login) and the site header (which starts the session and $conn)
include "../includes/auth.php";
include "../includes/header.php";

$search_term = "";

// --- SEARCH LOGIC ---

// Check if the user clicked the search button and typed something
if (isset($_POST['submit_search']) && !empty($_POST['search_term'])) {
    // real_escape_string helps prevent SQL injection by cleaning special characters
    $search_term = $conn->real_escape_string($_POST['search_term']);
    
    // The LIKE operator with % allows searching for a partial word anywhere in the title or description
    $result = $conn->query("SELECT * FROM rooms WHERE title LIKE '%$search_term%' OR description LIKE '%$search_term%' ORDER BY created_at DESC");

} elseif (isset($_POST['clear_search'])) {
    // If user clicks 'Clear', reset the list to show all rooms
    $result = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");

} else {
    // Default view: Show all rooms ordered by the most recently created
    $result = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");
}
?>

<div class="container">
    <div class="room-container">
        <h2 style="color:black; text-align:center;">Rooms</h2>
        
        <form action="" method="post" class="search-form">
            <a href="create_room.php" class="create-room"><i class="fa-solid fa-plus"></i> Create Room</a>
            
            <input type="text" name="search_term" id="search" placeholder="Search rooms...">
            
            <button type="submit" name="submit_search"><i class="fa-solid fa-magnifying-glass"></i></button>
            <button type="submit" name="clear_search">Clear</button>
        </form>

        <div class="rooms">
        <?php 
        // fetch_assoc() grabs one row at a time until there are no more rooms left in the results
        while ($room = $result->fetch_assoc()): 
        ?>
            <div class="card-room">
                <h4>
                    <a href="room.php?id=<?= $room['room_id'] ?>">
                        <?= htmlspecialchars($room['title']) ?>
                    </a>
                </h4>
                <p><?= htmlspecialchars($room['description']) ?></p>
                <?php if ($room['image']): ?>
                    <p style="color: #5a5de6; font-size: 14px; margin-top: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><i class="fa-solid fa-image"></i> Includes an image</p>
                <?php endif; ?>
                
            </div>
        <?php endwhile; ?>
        </div>
    </div>
</div>
</body>
</html>