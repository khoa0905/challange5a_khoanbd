<?php

namespace App\Controllers;

use PDO;

class AssignmentController
{
    private PDO $pdo;

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

    // GET /assignments
    public function list(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $assignments = get_all_assignments($pdo);
        $pageTitle   = 'Assignments';
        include __DIR__ . '/../views/assignments.php';
    }

    // POST /assignments  (teacher uploads a new assignment)
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

            if ($title === '') {
                $error = 'Please provide a title.';
            } else {
                $result = handle_file_upload(
                    $_FILES['assignment_file'] ?? null,
                    'uploads/assignments/',
                    self::ALLOWED_EXTS,
                    self::ALLOWED_MIMES
                );

                if (isset($result['error'])) {
                    $error = $result['error'];
                } else {
                    add_assignment($pdo, $title, $result['path'], $_SESSION['id']);
                    $success = 'Assignment uploaded successfully!';
                }
            }
        }

        $assignments = get_all_assignments($pdo);
        $pageTitle   = 'Assignments';
        include __DIR__ . '/../views/assignments.php';
    }

    // GET /assignment?id=X
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

    // POST /assignment?id=X  (student submits work)
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
                'uploads/submissions/',
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

        // Re-render detail page with error
        if ($_SESSION['role'] === 'teacher') {
            $submissions = get_submissions_by_assignment($pdo, $assignment_id);
        } else {
            $my_submission = get_student_submission($pdo, $assignment_id, $_SESSION['id']);
        }

        $pageTitle = 'Assignment: ' . $assignment['title'];
        include __DIR__ . '/../views/assignment_detail.php';
    }
}
