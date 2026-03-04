<?php
    $pageTitle = "User Directory"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>
<body>
    <h1>User Directory</h1>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'): ?>
        <a href="/manage-students">Add New Student</a><br><br>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php if (!empty($user['avatar_path'])): ?>
                                <img src="<?= htmlspecialchars($user['avatar_path']) ?>" alt="<?= htmlspecialchars($user['username']) ?>'s avatar" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <img src="uploads/avatars/default.jpg" alt="Default avatar" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                        <td>
                            <a href="/profile?id=<?= urlencode($user['id']) ?>">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No users found in the database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>