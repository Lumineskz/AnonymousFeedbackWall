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
    } elseif ($_GET['error'] === 'empty_fields') {
        $message = 'All fields are required!';
    } elseif ($_GET['error'] === 'update_failed') {
        $message = 'Update failed. Please try again.';
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
                                <button class="btn-edit" onclick="editFeedback(<?= $feedback['feedback_id'] ?>)">
                                    <i class="fa-solid fa-edit"></i> Edit
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

<!-- Edit Room Modal -->
<div id="editRoomModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditRoomModal()">&times;</span>
        <h2>Edit Room</h2>
        <form method="POST" action="../actions/admin_update_room.php">
            <input type="hidden" id="roomId" name="room_id">
            <div class="form-group">
                <label for="roomTitle">Title:</label>
                <input type="text" id="roomTitle" name="title" required>
            </div>
            <div class="form-group">
                <label for="roomDescription">Description:</label>
                <textarea id="roomDescription" name="description" rows="4" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit">Update Room</button>
                <button type="button" class="btn-cancel" onclick="closeEditRoomModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditUserModal()">&times;</span>
        <h2>Edit User</h2>
        <form method="POST" action="../actions/admin_update_user.php">
            <input type="hidden" id="userId" name="user_id">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" disabled>
            </div>
            <div class="form-group">
                <label for="userRole">Role:</label>
                <select id="userRole" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit">Update User</button>
                <button type="button" class="btn-cancel" onclick="closeEditUserModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Feedback Modal -->
<div id="editFeedbackModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditFeedbackModal()">&times;</span>
        <h2>Edit Feedback</h2>
        <form method="POST" action="../actions/admin_update_feedback.php">
            <input type="hidden" id="feedbackId" name="feedback_id">
            <div class="form-group">
                <label for="feedbackRoom">Room:</label>
                <input type="text" id="feedbackRoom" disabled>
            </div>
            <div class="form-group">
                <label for="feedbackAuthor">Author Name:</label>
                <input type="text" id="feedbackAuthor" name="display_name" required>
            </div>
            <div class="form-group">
                <label for="feedbackMessage">Message:</label>
                <textarea id="feedbackMessage" name="message" rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit">Update Feedback</button>
                <button type="button" class="btn-cancel" onclick="closeEditFeedbackModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 30px;
    border: 1px solid #888;
    width: 90%;
    max-width: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 20px;
}

.close:hover,
.close:focus {
    color: #000;
}

.modal h2 {
    margin-top: 0;
    color: #151515;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 14px;
}

.form-group input:disabled,
.form-group textarea:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-submit,
.btn-cancel {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    font-weight: 500;
}

.btn-submit {
    background-color: #5a5de6;
    color: white;
}

.btn-submit:hover {
    background-color: #4a4db8;
}

.btn-cancel {
    background-color: #e0e0e0;
    color: #333;
}

.btn-cancel:hover {
    background-color: #d0d0d0;
}
</style>

<script>
// Room Modal Functions
function editRoom(roomId) {
    fetch('../actions/admin_get_room.php?id=' + roomId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('roomId').value = data.room_id;
            document.getElementById('roomTitle').value = data.title;
            document.getElementById('roomDescription').value = data.description;
            document.getElementById('editRoomModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load room data');
        });
}

function closeEditRoomModal() {
    document.getElementById('editRoomModal').style.display = 'none';
}

// User Modal Functions
function editUser(userId) {
    fetch('../actions/admin_get_user.php?id=' + userId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userId').value = data.user_id;
            document.getElementById('username').value = data.username;
            document.getElementById('userRole').value = data.role;
            document.getElementById('editUserModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load user data');
        });
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

// Feedback Modal Functions
function editFeedback(feedbackId) {
    fetch('../actions/admin_get_feedback.php?id=' + feedbackId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('feedbackId').value = data.feedback_id;
            document.getElementById('feedbackRoom').value = data.title;
            document.getElementById('feedbackAuthor').value = data.display_name;
            document.getElementById('feedbackMessage').value = data.message;
            document.getElementById('editFeedbackModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load feedback data');
        });
}

function closeEditFeedbackModal() {
    document.getElementById('editFeedbackModal').style.display = 'none';
}

// Delete Functions
function deleteRoom(roomId, title) {
    if (confirm(`Are you sure you want to delete room "${title}"?`)) {
        window.location.href = '../actions/admin_delete_room.php?id=' + roomId;
    }
}

function deleteUser(userId, username) {
    if (confirm(`Are you sure you want to delete user "${username}"?`)) {
        window.location.href = '../actions/admin_delete_user.php?id=' + userId;
    }
}

function deleteFeedback(feedbackId) {
    if (confirm('Are you sure you want to delete this feedback?')) {
        window.location.href = '../actions/admin_delete_feedback.php?id=' + feedbackId;
    }
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    let roomModal = document.getElementById('editRoomModal');
    let userModal = document.getElementById('editUserModal');
    let feedbackModal = document.getElementById('editFeedbackModal');
    
    if (event.target === roomModal) {
        roomModal.style.display = 'none';
    }
    if (event.target === userModal) {
        userModal.style.display = 'none';
    }
    if (event.target === feedbackModal) {
        feedbackModal.style.display = 'none';
    }
}
</script>

</body>
</html>