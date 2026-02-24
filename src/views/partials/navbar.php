<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'guest'; 
?>

<style>
    .navbar {
        background-color: #333;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }
    .navbar a {
        float: left;
        display: block;
        color: white;
        text-align: center;
        padding: 14px 20px;
        text-decoration: none;
    }
    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }
    .navbar-right {
        float: right;
    }
</style>

<div class="navbar">
    <a href="index.php">Home</a>
    <a href="users.php">Users List</a> 
    <a href="assignments.php">Assignments</a> 
    <a href="challenges.php">Challenges</a> 
    <?php if ($role === 'teacher'): ?>
        <a href="manage_student.php">Manage Students</a> 
    <?php endif; ?>

    <div class="navbar-right">
        <?php if ($role !== 'guest'): ?>
            <a href="profile.php">My Profile</a> <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>