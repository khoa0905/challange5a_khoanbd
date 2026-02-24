<?php
    $pageTitle = "Login"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>
<h1>Login page</h1>
<form action="" method="post">
  <?php if (isset($error)): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <div>
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit">Login</button>
  </div>
</form>