<?php
session_start();
require_once "../config/db.php";
require_once "../services/assignments.php";
require_once "../services/submissions.php";
require_once "../utils/middleware.php";

require_auth();
$pdo = get_db_connection();

$assignment_id = $_GET['id'] ?? null;
if (!$assignment_id) {
    header('Location: assignments.php');
    exit;
}

$assignment = get_assignment_by_id($pdo, $assignment_id);
if (!$assignment) {
    header('Location: assignments.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['role'] !== 'student') {
        $error = "Only students can upload submissions.";
    } else {
        $submission_file = $_FILES['submission_file'] ?? null;

        if (!$submission_file || $submission_file['error'] !== UPLOAD_ERR_OK) {
            $error = "Please select a valid file to upload.";
        } else {
            $allowed_exts = ['pdf', 'doc', 'docx', 'zip', 'rar', 'txt'];
            $file_ext = strtolower(pathinfo($submission_file['name'], PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_exts)) {
                $error = "Invalid file type. Allowed: PDF, DOCX, TXT, ZIP, RAR.";
            } else {
                $upload_dir = 'uploads/submissions/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $new_file_name = bin2hex(random_bytes(16)) . '.' . $file_ext;
                $target_file = $upload_dir . $new_file_name;

                if (move_uploaded_file($submission_file['tmp_name'], $target_file)) {
                    add_submission($pdo, $assignment_id, $_SESSION['id'], $target_file);
                    
                    header("Location: assignment_detail.php?id=" . urlencode($assignment_id));
                    exit;
                } else {
                    $error = "Failed to save the file to the server.";
                }
            }
        }
    }
}

if ($_SESSION['role'] === 'teacher') {
    $submissions = get_submissions_by_assignment($pdo, $assignment_id);
} else {
    $my_submission = get_student_submission($pdo, $assignment_id, $_SESSION['id']);
}

$pageTitle = "Assignment: " . $assignment['title'];
include '../views/assignment_detail.php';
?>