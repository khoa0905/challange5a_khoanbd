<?php
session_start();
require_once "../config/db.php";
require_once "../services/users.php";
require_once "../utils/middleware.php";
require_role('teacher');

$pdo = get_db_connection();
$students = get_all_students($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if ($username === '' || $password === '' || $full_name === '' || $email === '') {
        $error = 'Please fill up all fields';
    } else {
        add_student($pdo, $username, $password, $full_name, $email, $phone);
        $success = 'Student added successfully';
    }
}

include '../views/manage_student.php';
?>