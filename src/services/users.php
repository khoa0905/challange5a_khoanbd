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

function is_username_taken($pdo, $username) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetchColumn() > 0;
}

function is_email_taken($pdo, $email) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
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

function update_student($pdo, $id, $username, $password, $full_name, $email, $phone) {
    if ($password !== '') {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, full_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'student'");
        return $stmt->execute([$username, password_hash($password, PASSWORD_BCRYPT), $full_name, $email, $phone, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'student'");
        return $stmt->execute([$username, $full_name, $email, $phone, $id]);
    }
}

function delete_user($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}
?>