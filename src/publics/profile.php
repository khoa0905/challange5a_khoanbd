<?php
session_start();
require_once "../config/db.php";
require_once "../services/users.php";
require_once "../services/messages.php";
require_once "../utils/middleware.php";

$pdo = get_db_connection();

require_auth();

$user_id = $_SESSION['id'];
$profile_id = $_GET['id'] ?? $user_id;
$profile = get_user_by_id($pdo, $profile_id);
$messages = get_visible_messages($pdo, $profile_id, $_SESSION['id']);


if (!$profile) {
    $error = 'User not found';
    include '../views/profile.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_auth();
    if ((isset($_SESSION['role']) && $_SESSION['role'] !== 'teacher') && $_SESSION['id'] != $profile_id) {
        $error = 'Student can only update their own profile';
        include '../views/profile.php';
        exit;
    }

    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $avatar_url_input = $_POST['avatar_url'] ?? '';
    $avatar_file_input = $_FILES['avatar_file'] ?? null;

    if ($email === '' || $phone === '') {
        $error = 'Please fill up all fields';
    } else {
        update_profile_by_id($pdo, $user_id, $email, $phone);
        if ($avatar_url_input !== '') {
            update_avatar_by_id($pdo, $user_id, $avatar_url_input);
        } else if ($avatar_file_input && $avatar_file_input['error'] === UPLOAD_ERR_OK) {
            $file_extension = strtolower(pathinfo($avatar_file_input['name'], PATHINFO_EXTENSION));
            

            $upload_dir = 'uploads/avatars/';
            $file_name = basename($avatar_file_input['name']);
            $target_file = $upload_dir . bin2hex(random_bytes(16)) . '.' . $file_extension;
            if (move_uploaded_file($avatar_file_input['tmp_name'], $target_file)) {
                update_avatar_by_id($pdo, $user_id, $target_file);
            }
        }
        header('Location: profile.php');
        exit;
    }
}

include '../views/profile.php';
?>