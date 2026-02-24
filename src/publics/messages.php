<?php
session_start();
require_once "../config/db.php";
require_once "../services/messages.php";
require_once "../utils/middleware.php";

require_auth(); 
$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['id'];

    if ($action === 'send') {
        $receiver_id = $_POST['receiver_id'] ?? '';
        $message = trim($_POST['message'] ?? '');

        if ($receiver_id && $message !== '') {
            send_message($pdo, $user_id, $receiver_id, $message);
        }
        header("Location: profile.php?id=" . urlencode($receiver_id));
        exit;
    }

    if ($action === 'delete') {
        $message_id = $_POST['message_id'] ?? '';
        $receiver_id = $_POST['receiver_id'] ?? '';

        $msg = get_message_by_id($pdo, $message_id);
        if ($msg && $msg['sender_id'] == $user_id) {
            delete_message($pdo, $message_id);
        }
        header("Location: profile.php?id=" . urlencode($receiver_id));
        exit;
    }

    if ($action === 'edit') {
        $message_id = $_POST['message_id'] ?? '';
        $new_message = trim($_POST['new_message'] ?? '');
        $receiver_id = $_POST['receiver_id'] ?? '';

        // SECURITY: Verify ownership before editing
        $msg = get_message_by_id($pdo, $message_id);
        if ($msg && $msg['sender_id'] == $user_id && $new_message !== '') {
            edit_message($pdo, $message_id, $new_message);
        }
        header("Location: profile.php?id=" . urlencode($receiver_id));
        exit;
    }
}

header("Location: index.php");
exit;
?>