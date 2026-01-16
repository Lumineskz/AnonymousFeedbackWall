<?php
// Include admin authentication
include "../includes/admin-auth.php";
include "../includes/admin-header.php";
include "../config/config.php";

$section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';

// Get stats for dashboard
$roomCount = $conn->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'];
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$feedbackCount = $conn->query("SELECT COUNT(*) as count FROM feedback")->fetch_assoc()['count'];

// Display messages
$message = '';
$messageType = '';

if (isset($_GET['deleted'])) {
    $message = 'Item deleted successfully!';
    $messageType = 'success';
} elseif (isset($_GET['updated'])) {
    $message = 'Item updated successfully!';
    $messageType = 'success';
} elseif (isset($_GET['error'])) {
    $message = 'An error occurred. Please try again.';
    $messageType = 'error';
    if ($_GET['error'] === 'cannot_delete_self') {
        $message = 'You cannot delete your own account!';
    } elseif ($_GET['error'] === 'cannot_demote_self') {
        $message = 'You cannot demote yourself from admin!';
    }
}
?>

<?php if ($message): ?>
    <div class="message message-<?= $messageType ?>" style="margin-bottom: 20px;">
        <?= htmlspecialchars($message) ?>
        <button onclick="this.parentElement.style.display='none';" style="float: right; background: none; border: none; color: inherit; cursor: pointer; font-size: 20px;">&times;</button>
    </div>
<?php endif; ?>

<?php if ($section === 'dashboard'): ?>
    <h1 style="color: #151515; margin-bottom: 30px;">Dashboard</h1>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Total Rooms</h3>
            <div class="stat"><?= $roomCount ?></div>
            <p>Active rooms in the system</p>
        </div>
        
        <div class="dashboard-card">
            <h3>Total Users</h3>
            <div class="stat"><?= $userCount ?></div>
            <p>Registered users</p>
        </div>
        
        <div class="dashboard-card">
            <h3>Total Feedback</h3>
            <div class="stat"><?= $feedbackCount ?></div>
            <p>Submitted feedbacks</p>
        </div>
    </div>

<?php elseif ($section === 'rooms'): ?>
    <div class="admin-section">
        <h2><i class="fa-solid fa-door-open"></i> Manage Rooms</h2>
        
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Room ID</th>
                        <th>Title</th>
                        <th>Creator</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rooms = $conn->query(
                        "SELECT r.room_id, r.title, r.description, r.created_at, u.username 
                         FROM rooms r 
                         INNER JOIN users u ON r.user_id = u.user_id 
                         ORDER BY r.created_at DESC"
                    );
                    
                    while ($room = $rooms->fetch_assoc()):
                    ?>
                    <tr>
                        <td>#<?= $room['room_id'] ?></td>
                        <td><?= htmlspecialchars(substr($room['title'], 0, 30)) ?></td>
                        <td><?= htmlspecialchars($room['username']) ?></td>
                        <td><?= htmlspecialchars(substr($room['description'], 0, 40)) ?>...</td>
                        <td><?= date('M d, Y', strtotime($room['created_at'])) ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="editRoom(<?= $room['room_id'] ?>)">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </button>
                                <button class="btn-delete" onclick="deleteRoom(<?= $room['room_id'] ?>, '<?= htmlspecialchars($room['title']) ?>')">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($section === 'users'): ?>
    <div class="admin-section">
        <h2><i class="fa-solid fa-users"></i> Manage Users</h2>
        
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $conn->query(
                        "SELECT user_id, username, role, created_at FROM users ORDER BY created_at DESC"
                    );
                    
                    while ($user = $users->fetch_assoc()):
                    ?>
                    <tr>
                        <td>#<?= $user['user_id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <form method="POST" action="../actions/admin_update_user.php" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                <select name="role" style="padding: 4px 8px; border: 1px solid #7370be; border-radius: 3px; background-color: #f0eef9;" onchange="this.form.submit()">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="editUser(<?= $user['user_id'] ?>)">
                                    <i class="fa-solid fa-edit"></i> Edit
                                </button>
                                <button class="btn-delete" onclick="deleteUser(<?= $user['user_id'] ?>, '<?= htmlspecialchars($user['username']) ?>')">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($section === 'feedback'): ?>
    <div class="admin-section">
        <h2><i class="fa-solid fa-comments"></i> Manage Feedback</h2>
        
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Room</th>
                        <th>Author</th>
                        <th>Message</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $feedbacks = $conn->query(
                        "SELECT f.feedback_id, f.message, f.display_name, f.created_at, r.title, r.room_id 
                         FROM feedback f 
                         INNER JOIN rooms r ON f.room_id = r.room_id 
                         ORDER BY f.created_at DESC"
                    );
                    
                    while ($feedback = $feedbacks->fetch_assoc()):
                    ?>
                    <tr>
                        <td>#<?= $feedback['feedback_id'] ?></td>
                        <td>
                            <a href="../pages/room.php?id=<?= $feedback['room_id'] ?>" style="color: #5a5de6; text-decoration: none;">
                                <?= htmlspecialchars(substr($feedback['title'], 0, 25)) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($feedback['display_name']) ?></td>
                        <td><?= htmlspecialchars(substr($feedback['message'], 0, 40)) ?>...</td>
                        <td><?= date('M d, Y H:i', strtotime($feedback['created_at'])) ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="viewFeedback(<?= $feedback['feedback_id'] ?>)">
                                    <i class="fa-solid fa-eye"></i> View
                                </button>
                                <button class="btn-delete" onclick="deleteFeedback(<?= $feedback['feedback_id'] ?>)">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

    </main>
</div>

<script>
function deleteRoom(roomId, title) {
    if (confirm(`Are you sure you want to delete room "${title}"?`)) {
        window.location.href = '../actions/admin_delete_room.php?id=' + roomId;
    }
}

function editRoom(roomId) {
    alert('Edit functionality coming soon');
}

function deleteUser(userId, username) {
    if (confirm(`Are you sure you want to delete user "${username}"?`)) {
        window.location.href = '../actions/admin_delete_user.php?id=' + userId;
    }
}

function editUser(userId) {
    // Get the current user data via AJAX or form
    fetch('../actions/admin_get_user.php?id=' + userId)
        .then(response => response.json())
        .then(data => {
            showEditUserModal(data);
        })
        .catch(error => console.error('Error:', error));
}

function deleteFeedback(feedbackId) {
    if (confirm('Are you sure you want to delete this feedback?')) {
        window.location.href = '../actions/admin_delete_feedback.php?id=' + feedbackId;
    }
}

function viewFeedback(feedbackId) {
    alert('View functionality coming soon');
}
</script>

</body>
</html>
