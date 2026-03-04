<?php
    $pageTitle = "Manage Students"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>
<h1>Student Pages</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><strong><?= htmlspecialchars($error) ?></strong></p>
<?php endif; ?>

<?php if (isset($success)): ?>
    <p style="color: green;"><strong><?= htmlspecialchars($success) ?></strong></p>
<?php endif; ?>



<form action="" method="POST">
    <div>
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required>
    </div>
    <br>
    <div>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required>
    </div>
    <br>
    <div>
        <label for="full_name">Full Name:</label><br>
        <input type="text" id="full_name" name="full_name" required>
    </div>
    <br>
    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required>
    </div>
    <br>
    <div>
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone">
    </div>
    <br>
    <button type="submit">Create Student</button>
</form>

<br>
<a href="/">Back to Dashboard</a>