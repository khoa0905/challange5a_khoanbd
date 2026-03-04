<?php

namespace App\Controllers;

use PDO;

class MessageController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function handle(): void
    {
        $pdo = $this->pdo;
        require_auth();

        $action  = $_POST['action']  ?? '';
        $user_id = $_SESSION['id'];

        switch ($action) {
            case 'send':
                $this->send($pdo, $user_id);
                break;

            case 'delete':
                $this->delete($pdo, $user_id);
                break;

            case 'edit':
                $this->edit($pdo, $user_id);
                break;

            default:
                header('Location: /');
                exit;
        }
    }

    private function send(\PDO $pdo, int $user_id): void
    {
        $receiver_id = $_POST['receiver_id'] ?? '';
        $message     = trim($_POST['message'] ?? '');

        if ($receiver_id && $message !== '') {
            send_message($pdo, $user_id, $receiver_id, $message);
        }

        header('Location: /profile?id=' . urlencode($receiver_id));
        exit;
    }

    private function delete(\PDO $pdo, int $user_id): void
    {
        $message_id  = $_POST['message_id']  ?? '';
        $receiver_id = $_POST['receiver_id'] ?? '';

        $msg = get_message_by_id($pdo, $message_id);
        if ($msg && $msg['sender_id'] == $user_id) {
            delete_message($pdo, $message_id);
        }

        header('Location: /profile?id=' . urlencode($receiver_id));
        exit;
    }

    private function edit(\PDO $pdo, int $user_id): void
    {
        $message_id  = $_POST['message_id']  ?? '';
        $new_message = trim($_POST['new_message'] ?? '');
        $receiver_id = $_POST['receiver_id'] ?? '';

        $msg = get_message_by_id($pdo, $message_id);
        if ($msg && $msg['sender_id'] == $user_id && $new_message !== '') {
            edit_message($pdo, $message_id, $new_message);
        }

        header('Location: /profile?id=' . urlencode($receiver_id));
        exit;
    }
}
