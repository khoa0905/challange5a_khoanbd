<?php
session_start();
require_once "../services/auth.php";
require_once "../config/db.php";

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Username and password required';
        include '../views/login.php';
        exit;
    }

    $user = authenticate_user($pdo, $username, $password);
    if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

include '../views/login.php';
?>