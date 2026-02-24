<?php
function authenticate_user($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT id, role, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}
?>