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
        } elseif (is_username_taken($pdo, $username)) {
            $error = 'Username already exists';
        } elseif (is_email_taken($pdo, $email)) {
            $error = 'Email already exists';
        } else {
            add_student($pdo, $username, $password, $full_name, $email, $phone);
            $success = 'Student added successfully';
        }

        $students = get_all_students($pdo);
        $pageTitle = 'Manage Students';
        include __DIR__ . '/../views/manage_student.php';
    }

    public function editForm(): void
    {
        $pdo = $this->pdo;
        require_role('teacher');

        $id = $_GET['id'] ?? null;
        $student = get_student_by_id($pdo, $id);
        
        if (!$student) {
            header('Location: /users');
            exit;
        }

        $pageTitle = 'Edit Student';
        include __DIR__ . '/../views/edit_student.php';
    }

    public function editStudent(): void
    {
        $pdo = $this->pdo;
        require_role('teacher');

        $id = $_GET['id'] ?? null;
        $student = get_student_by_id($pdo, $id);
        
        if (!$student) {
            header('Location: /users');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if ($username === '' || $full_name === '' || $email === '') {
            $error = 'Please fill up all required fields';
        } elseif ($username !== $student['username'] && is_username_taken($pdo, $username)) {
            $error = 'Username already exists';
        } elseif ($email !== $student['email'] && is_email_taken($pdo, $email)) {
            $error = 'Email already exists';
        } else {
            update_student($pdo, $id, $username, $password, $full_name, $email, $phone);
            $success = 'Student updated successfully';
            $student = get_student_by_id($pdo, $id);
        }

        $pageTitle = 'Edit Student';
        include __DIR__ . '/../views/edit_student.php';
    }

    public function deleteStudent(): void
    {
        $pdo = $this->pdo;
        require_role('teacher');

        $id = $_POST['id'] ?? null;
        $student = get_student_by_id($pdo, $id);
        if ($student) {
            delete_user($pdo, $id);
        }
        
        header('Location: /users');
        exit;
    }
}
?>