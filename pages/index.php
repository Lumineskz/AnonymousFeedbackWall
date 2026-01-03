<head>
    <title>Room Feedback</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<?php 
// pages/index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anonymous Feedback Wall</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>
    <div class="container">
        <h1 class="header-msg">Anonymous Feedback Wall</h1>
        <p class="description-msg">Share your thoughts freely — no logins, just honesty. You can also upload an image!</p>

        <!-- Feedback Form -->
        <!-- CRITICAL: Add enctype="multipart/form-data" to allow file uploads -->
        <form action="../actions/submit_feedback.php" method="POST" class="feedback-form" enctype="multipart/form-data">
            
            <label class="checkbox-wrapper">
                <input type="checkbox" name="anonymous" checked value="1"/>
                <div class="checkmark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path
                        d="M20 6L9 17L4 12"
                        stroke-width="3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                    </svg>
                </div>
                <span class="label">Post as Anon</span>
            </label>
            
            <textarea name="message" placeholder="Write your feedback here..." required></textarea>
            
            <!-- NEW: Image Upload Input -->
            <label for="image-upload" class="file-label">
                🖼️ Optional Image (5MB max)
            </label>
            <!-- The accept attribute restricts client-side file selection -->
            <input type="file" id="image-upload" name="image" accept="image/jpeg, image/png, image/gif" class="file-input">
            
            <button class="ui-btn" type="submit" name="submit_feedback">
                <span> Submit </span>
            </button>
        </form>

        <!-- Confirmation and Error Area -->
        <?php if(isset($_GET['success'])): ?>
            <p class="success">✅ Thank you! Your feedback was submitted.</p>
        <?php elseif(isset($_GET['error'])): ?>
            <p class="error">⚠️ Error! 
            <?php 
                $error = htmlspecialchars($_GET['error']);
                if ($error == 'emptyfields') echo "Please enter a message or upload an image.";
                elseif ($error == 'invalidfiletype') echo "Invalid file type. Only JPG, PNG, or GIF allowed.";
                elseif ($error == 'filetoobig') echo "File size exceeds the 5MB limit.";
                elseif ($error == 'uploadfailed') echo "File upload failed on the server. Try again.";
                elseif ($error == 'dberror') echo "A database error occurred during submission.";
                else echo "An unknown error occurred.";
            ?>
            </p>
        <?php endif; ?>

        <a href="wall.php" class="view-wall-btn">View Feedback Wall →</a>
    </div>
</body>
</html>