<?php

namespace App\Controllers;

use PDO;

class ChallengeController
{
    private PDO $pdo;

    private const STORAGE_DIR = __DIR__ . '/../challenge_files';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private static function findChallengeFile(int $id): ?string
    {
        $dir = self::STORAGE_DIR . "/{$id}";
        if (!is_dir($dir)) {
            return null;
        }
        $files = glob($dir . '/*.txt');
        return $files[0] ?? null;
    }

    public function list(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $challenges   = get_all_challenges($pdo);
        $poem_content = null;
        $download_link = null;
        $pageTitle    = 'Challenges';

        include __DIR__ . '/../views/challenges.php';
    }

    public function handle(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $action = $_POST['action'] ?? '';

        if ($action === 'create_challenge') {
            $this->create();
            return;
        }

        if ($action === 'submit_guess') {
            $this->guess();
            return;
        }

        header('Location: /challenges');
        exit;
    }

    public function download(array $matches): void
    {
        require_auth();

        $id       = (int) $matches['id'];
        $filename = urldecode($matches['name']);

        $challenge = get_challenge_by_id($this->pdo, $id);
        if (!$challenge) {
            http_response_code(404);
            echo 'Challenge not found.';
            return;
        }

        $file = realpath(self::STORAGE_DIR . "/{$id}/{$filename}");
        $base = realpath(self::STORAGE_DIR);

        if (!$file || !$base || strpos($file, $base) !== 0 || !is_file($file)) {
            http_response_code(404);
            echo 'File not found.';
            return;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    private function create(): void
    {
        $pdo = $this->pdo;

        if ($_SESSION['role'] !== 'teacher') {
            header('Location: /challenges');
            exit;
        }

        $hint = trim($_POST['hint'] ?? '');
        $file = $_FILES['challenge_file'] ?? null;

        if ($hint === '' || !$file || $file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Please provide both a hint and a .txt file.';
            $challenges   = get_all_challenges($pdo);
            $poem_content = null;
            $download_link = null;
            $pageTitle    = 'Challenges';
            include __DIR__ . '/../views/challenges.php';
            return;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'txt') {
            $error = 'Only .txt files are allowed.';
            $challenges   = get_all_challenges($pdo);
            $poem_content = null;
            $download_link = null;
            $pageTitle    = 'Challenges';
            include __DIR__ . '/../views/challenges.php';
            return;
        }

        $challenge_id = add_challenge($pdo, $_SESSION['id'], $hint);

        $dir = self::STORAGE_DIR . "/{$challenge_id}";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $original_name = $file['name'];  
        $dest          = $dir . '/' . $original_name;

        move_uploaded_file($file['tmp_name'], $dest);

        header('Location: /challenges');
        exit;
    }

    private function guess(): void
    {
        $pdo = $this->pdo;

        $challenge_id = $_POST['challenge_id'] ?? null;
        $guess        = trim($_POST['guess'] ?? '');

        if (!$challenge_id || $guess === '') {
            header('Location: /challenges');
            exit;
        }

        $challenge = get_challenge_by_id($pdo, (int) $challenge_id);
        if (!$challenge) {
            header('Location: /challenges');
            exit;
        }

        $abs_path = self::findChallengeFile((int) $challenge_id);
        if (!$abs_path) {
            header('Location: /challenges');
            exit;
        }

        $answer = pathinfo(basename($abs_path), PATHINFO_FILENAME);

        $poem_content  = null;
        $download_link = null;
        $error         = null;

        if (strcasecmp($guess, $answer) === 0) {
            $poem_content  = file_get_contents($abs_path);
            $download_link = '/challenge/' . $challenge['id'] . '/' . urlencode(basename($abs_path));
        } else {
            $error = 'Wrong answer! Try again.';
        }

        $challenges = get_all_challenges($pdo);
        $pageTitle  = 'Challenges';
        include __DIR__ . '/../views/challenges.php';
    }
}
?>