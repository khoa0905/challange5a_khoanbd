<?php

namespace App\Controllers;

use PDO;

class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function showLogin(): void
    {
        $pageTitle = 'Login';
        include __DIR__ . '/../views/login.php';
    }

    public function login(): void
    {
        $pdo = $this->pdo;

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Username and password required';
            include __DIR__ . '/../views/login.php';
            return;
        }

        $user = authenticate_user($pdo, $username, $password);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: /');
            exit;
        }

        $error = 'Invalid username or password';
        include __DIR__ . '/../views/login.php';
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header('Location: /login');
        exit;
    }
}
?>