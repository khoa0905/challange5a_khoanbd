<?php
session_start();
require_once "../config/db.php";
require_once "../services/assignments.php";
require_once "../utils/middleware.php";

require_auth();
$pdo = get_db_connection();
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['role'] !== 'teacher') {
        $error = "Only teachers can upload assignments.";
    } else {
        $title = $_POST['title'] ?? '';
        $assignment_file = $_FILES['assignment_file'] ?? null;

        if (!$assignment_file) {
            $error = "No file uploaded.";
        } else if ($assignment_file['error'] !== UPLOAD_ERR_OK) {
            $error = "File upload error: " . $assignment_file['error'];
        } else if ($title === '') {
            $error = "Please provide a title.";
        }

        if ($title === '' || !$assignment_file || $assignment_file['error'] !== UPLOAD_ERR_OK) {
        } else {
            $allowed_extensions = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
            $file_extension = strtolower(pathinfo($assignment_file['name'], PATHINFO_EXTENSION));
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $assignment_file['tmp_name']);
            finfo_close($finfo);
            
            $allowed_mimes = [
                'application/pdf', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                'text/plain', 
                'application/zip', 
                'application/x-rar-compressed',
                'application/octet-stream' 
            ];

            if (!in_array($file_extension, $allowed_extensions) || !in_array($mime_type, $allowed_mimes)) {
                $error = 'Invalid file type. Allowed: PDF, DOCX, TXT, ZIP, RAR.';
            } else {
                $upload_dir = 'uploads/assignments/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $target_file = $upload_dir . bin2hex(random_bytes(16)) . '.' . $file_extension;
                
                if (move_uploaded_file($assignment_file['tmp_name'], $target_file)) {
                    add_assignment($pdo, $title, $target_file, $_SESSION['id']);
                    $success = 'Assignment uploaded successfully!';
                } else {
                    $error = 'Failed to save the uploaded file.';
                }
            }
        }
    }
}

$assignments = get_all_assignments($pdo);

$pageTitle = 'Assignments';
include '../views/assignments.php';
?>