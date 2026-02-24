<?php
    $pageTitle = "User Profile"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>
<body>
    <h1>User Profile</h1>

    <?php if (!$profile): ?>
        <p style="color: red;"><strong>User not found.</strong></p>
        <a href="index.php">Back to Dashboard</a>
    <?php else: ?>

        <?php if (isset($error)): ?>
            <p style="color: red;"><strong><?= htmlspecialchars($error) ?></strong></p>
        <?php endif; ?>

        <div style="border: 1px solid #000; padding: 20px; width: 300px;">
            
            <div style="text-align: center; margin-bottom: 15px;">
                <?php if (!empty($profile['avatar_path'])): ?>
                    <img src="<?= htmlspecialchars($profile['avatar_path']) ?>" alt="<?= htmlspecialchars($profile['username']) ?>'s avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                    <img src="uploads/avatars/default.png" alt="Default avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                <?php endif; ?>
            </div>

            <p><strong>Username:</strong> <?= htmlspecialchars($profile['username']) ?></p>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($profile['full_name']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($profile['role'])) ?></p>

            <hr>

            <?php if ($_SESSION['id'] == $profile['id']): ?>
                <form action="profile.php?id=<?= urlencode($profile['id']) ?>" method="POST" enctype="multipart/form-data">
                    
                    <div>
                        <label for="email"><strong>Email:</strong></label><br>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($profile['email']) ?>" required>
                    </div>
                    <br>
                    <div>
                        <label for="phone"><strong>Phone:</strong></label><br>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>" required>
                    </div>
                    <br>
                    <hr>
                    <div>
                        <label><strong>Avatar URL:</strong></label><br>
                        <input type="text" name="avatar_url" placeholder="http://...">
                    </div>
                    <br>
                    <div>
                        <label><strong>Or Upload File:</strong></label><br>
                        <input type="file" name="avatar_file" accept="image/*">
                    </div>
                    <br>
                    <button type="submit">Save Changes</button>
                </form>

            <?php else: ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($profile['phone']) ?></p>
                

        <br>
        <a href="index.php">Back to Dashboard</a>

    <?php endif; ?>
    <hr>
    <h4>Message Board</h4>

    <?php if ($_SESSION['id'] != $profile['id']): ?>
        <form action="messages.php" method="POST" style="margin-bottom: 20px;">
            <input type="hidden" name="action" value="send">
            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($profile['id']) ?>">
            <textarea name="message" rows="3" cols="40" placeholder="Leave a message..." required></textarea><br>
            <button type="submit">Send Message</button>
        </form>
    <?php endif; ?>

    <div class="message-list">
        <?php if (empty($messages)): ?>
            <p>No messages yet. Be the first to say hello!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <p>
                        <strong><?= htmlspecialchars($msg['sender_username']) ?></strong> 
                        <small>(<?= htmlspecialchars($msg['created_at']) ?>)</small>
                    </p>
                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>

                    <?php if ($_SESSION['id'] == $msg['sender_id']): ?>
                        <div style="display: flex; gap: 10px;">
                            
                            <form action="messages.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                                <input type="hidden" name="receiver_id" value="<?= $profile['id'] ?>">
                                <button type="submit" style="color: red;">Delete</button>
                            </form>

                            <form action="messages.php" method="POST" onsubmit="let newMsg = prompt('Edit your message:', '<?= htmlspecialchars(addslashes($msg['message'])) ?>'); if(newMsg) { this.new_message.value = newMsg; return true; } return false;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                                <input type="hidden" name="receiver_id" value="<?= $profile['id'] ?>">
                                <input type="hidden" name="new_message" value="">
                                <button type="submit">Edit</button>
                            </form>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    </div>
</body>
</html>