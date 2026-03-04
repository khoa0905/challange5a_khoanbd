<?php

namespace App\Controllers;

use PDO;

class UserController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function list(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $users = get_all_users($pdo);
        $pageTitle = 'User Directory';
        include __DIR__ . '/../views/users.php';
    }

    public function manageForm(): void
    {
        $pdo = $this->pdo;
        require_role('teacher');

        $students = get_all_students($pdo);
        $pageTitle = 'Manage Students';
        include __DIR__ . '/../views/manage_student.php';
    }

    public function addStudent(): void
    {
        $pdo = $this->pdo;
        require_role('teacher');

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

        $students = get_all_students($pdo);
        $pageTitle = 'Manage Students';
        include __DIR__ . '/../views/manage_student.php';
    }
}
