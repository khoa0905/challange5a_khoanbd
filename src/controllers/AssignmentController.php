<?php

namespace App\Controllers;

use PDO;

class AssignmentController
{
    private PDO $pdo;
    private const STORAGE_DIR = __DIR__ . '/../storage';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private const ALLOWED_EXTS = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];

    private const ALLOWED_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed',
        'application/octet-stream',
    ];

    public function list(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $assignments = get_all_assignments($pdo);
        $pageTitle   = 'Assignments';
        include __DIR__ . '/../views/assignments.php';
    }

    public function upload(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $error   = null;
        $success = null;

        if ($_SESSION['role'] !== 'teacher') {
            $error = 'Only teachers can upload assignments.';
        } else {
            $title = $_POST['title'] ?? '';
            $description = trim($_POST['description'] ?? '') ?: null;

            if ($title === '') {
                $error = 'Please provide a title.';
            } else {
                $result = handle_file_upload(
                    $_FILES['assignment_file'] ?? null,
                    self::STORAGE_DIR . '/assignments/',
                    self::ALLOWED_EXTS,
                    self::ALLOWED_MIMES
                );

                if (isset($result['error'])) {
                    $error = $result['error'];
                } else {
                    add_assignment($pdo, $title, $description, $result['path'], $_SESSION['id']);
                    $success = 'Assignment uploaded successfully!';
                }
            }
        }

        $assignments = get_all_assignments($pdo);
        $pageTitle   = 'Assignments';
        include __DIR__ . '/../views/assignments.php';
    }

    public function detail(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $assignment_id = $_GET['id'] ?? null;
        if (!$assignment_id) {
            header('Location: /assignments');
            exit;
        }

        $assignment = get_assignment_by_id($pdo, $assignment_id);
        if (!$assignment) {
            header('Location: /assignments');
            exit;
        }

        if ($_SESSION['role'] === 'teacher') {
            $submissions = get_submissions_by_assignment($pdo, $assignment_id);
        } else {
            $my_submission = get_student_submission($pdo, $assignment_id, $_SESSION['id']);
        }

        $pageTitle = 'Assignment: ' . $assignment['title'];
        include __DIR__ . '/../views/assignment_detail.php';
    }

    public function submit(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $assignment_id = $_GET['id'] ?? null;
        if (!$assignment_id) {
            header('Location: /assignments');
            exit;
        }

        $assignment = get_assignment_by_id($pdo, $assignment_id);
        if (!$assignment) {
            header('Location: /assignments');
            exit;
        }

        $error = null;

        if ($_SESSION['role'] !== 'student') {
            $error = 'Only students can upload submissions.';
        } else {
            $result = handle_file_upload(
                $_FILES['submission_file'] ?? null,
                self::STORAGE_DIR . '/submissions/',
                self::ALLOWED_EXTS
            );

            if (isset($result['error'])) {
                $error = $result['error'];
            } else {
                add_submission($pdo, $assignment_id, $_SESSION['id'], $result['path']);
                header('Location: /assignment?id=' . urlencode($assignment_id));
                exit;
            }
        }

        if ($_SESSION['role'] === 'teacher') {
            $submissions = get_submissions_by_assignment($pdo, $assignment_id);
        } else {
            $my_submission = get_student_submission($pdo, $assignment_id, $_SESSION['id']);
        }

        $pageTitle = 'Assignment: ' . $assignment['title'];
        include __DIR__ . '/../views/assignment_detail.php';
    }

    public function downloadAssignment(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $assignment_id = $_GET['id'] ?? null;
        if (!$assignment_id) {
            http_response_code(404);
            return;
        }

        $assignment = get_assignment_by_id($pdo, $assignment_id);
        if (!$assignment) {
            http_response_code(404);
            return;
        }

        $file = $this->resolveDownloadPath($assignment['file_path']);
        if ($file === null) {
            http_response_code(404);
            return;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    public function downloadSubmission(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $submission_id = $_GET['id'] ?? null;
        if (!$submission_id) {
            http_response_code(404);
            return;
        }

        $submission = get_submission_by_id($pdo, $submission_id);
        if (!$submission) {
            http_response_code(404);
            return;
        }

        if ($_SESSION['role'] === 'teacher') {
            if ((int) $submission['teacher_id'] !== (int) $_SESSION['id']) {
                http_response_code(403);
                return;
            }
        } elseif ((int) $submission['student_id'] !== (int) $_SESSION['id']) {
            http_response_code(403);
            return;
        }

        $file = $this->resolveDownloadPath($submission['file_path']);
        if ($file === null) {
            http_response_code(404);
            return;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    private function resolveDownloadPath(string $storedPath): ?string
    {
        $baseStorage = realpath(self::STORAGE_DIR);

        if ($baseStorage === false) {
            return null;
        }

        $candidate = realpath($storedPath);
        if ($candidate && str_starts_with($candidate, $baseStorage) && is_file($candidate)) {
            return $candidate;
        }

        return null;
    }
}
?>
