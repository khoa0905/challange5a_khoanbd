<?php

namespace App\Controllers;

use PDO;

class ProfileController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function show(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $user_id = $_SESSION['id'];
        $profile_id = $_GET['id'] ?? $user_id;
        $profile = get_user_by_id($pdo, $profile_id);
        $messages  = get_visible_messages($pdo, $profile_id, $_SESSION['id']);

        if (!$profile) {
            $error = 'User not found';
        }

        $pageTitle = 'User Profile';
        include __DIR__ . '/../views/profile.php';
    }

    public function update(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $user_id = $_SESSION['id'];
        $profile_id = $_GET['id'] ?? $user_id;
        $profile = get_user_by_id($pdo, $profile_id);
        $messages = get_visible_messages($pdo, $profile_id, $_SESSION['id']);

        if (!$profile) {
            $error = 'User not found';
            $pageTitle = 'User Profile';
            include __DIR__ . '/../views/profile.php';
            return;
        }

        if ($_SESSION['role'] !== 'teacher' && $_SESSION['id'] != $profile_id) {
            $error = 'Student can only update their own profile';
            $pageTitle = 'User Profile';
            include __DIR__ . '/../views/profile.php';
            return;
        }

        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $avatar_url_input = $_POST['avatar_url'] ?? '';
        $avatar_file_input = $_FILES['avatar_file'] ?? null;

        if ($email === '' || $phone === '') {
            $error = 'Please fill up all fields';
            $pageTitle = 'User Profile';
            include __DIR__ . '/../views/profile.php';
            return;
        }

        update_profile_by_id($pdo, $user_id, $email, $phone);

        if ($avatar_url_input !== '') {
            $download = download_avatar_from_url($avatar_url_input);
            if (isset($download['path'])) {
                update_avatar_by_id($pdo, $user_id, $download['path']);
            } elseif (isset($download['error'])) {
                $error = $download['error'];
                $pageTitle = 'User Profile';
                $profile = get_user_by_id($pdo, $profile_id);
                include __DIR__ . '/../views/profile.php';
                return;
            }
        } elseif ($avatar_file_input && $avatar_file_input['error'] === UPLOAD_ERR_OK) {
            $result = handle_file_upload(
                $avatar_file_input,
                'uploads/avatars/',
                ['jpg', 'jpeg', 'png', 'gif', 'webp']
            );
            if (isset($result['path'])) {
                update_avatar_by_id($pdo, $user_id, $result['path']);
            }
        }

        header('Location: /profile');
        exit;
    }
}
?>