<?php
function get_user_by_id($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id, username, full_name, avatar_path, email, phone, role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_all_users($pdo) {
    $stmt = $pdo->query("SELECT id, username, full_name, avatar_path, email, phone, role FROM users");
    return $stmt->fetchAll();
}

function get_student_by_id($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id, username, full_name, avatar_path, email, phone FROM users WHERE id = ? AND role = 'student'");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_all_students($pdo) {
    $stmt = $pdo->query("SELECT id, username, full_name, avatar_path, email, phone FROM users WHERE role = 'student'");
    return $stmt->fetchAll();
}

function add_student($pdo, $username, $password, $full_name, $email, $phone) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email, phone) VALUES (?, ?, 'student', ?, ?, ?)");
    $stmt->execute([$username, password_hash($password, PASSWORD_BCRYPT), $full_name, $email, $phone]);
    return $pdo->lastInsertId();
}

function update_profile_by_id($pdo, $id, $email, $phone) {
    $stmt = $pdo->prepare("UPDATE users SET email = ?, phone = ? WHERE id = ?");
    return $stmt->execute([$email, $phone, $id]);
}

function update_avatar_by_id($pdo, $id, $avatar_path) {
    $stmt = $pdo->prepare("UPDATE users SET avatar_path = ? WHERE id = ?");
    return $stmt->execute([$avatar_path, $id]);
}
?>