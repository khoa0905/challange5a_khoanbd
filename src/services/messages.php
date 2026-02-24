<?php
function get_message_by_id($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id, sender_id, receiver_id, message FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function send_message($pdo, $sender_id, $receiver_id, $message) {
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    return $stmt->execute([$sender_id, $receiver_id, $message]);
}

function edit_message($pdo, $message_id, $new_message) {
    $stmt = $pdo->prepare("UPDATE messages SET message = ? WHERE id = ?");
    return $stmt->execute([$new_message, $message_id]);
}

function delete_message($pdo, $message_id) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    return $stmt->execute([$message_id]);
}

function get_received_messages($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT m.id, m.message, m.sender_id, m.created_at, u.username AS sender_username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = ? ORDER BY m.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_visible_messages($pdo, $profile_id, $viewer_id) {
    $stmt = $pdo->prepare("
        SELECT m.id, m.message, m.created_at, m.sender_id, u.username AS sender_username 
        FROM messages m 
        JOIN users u ON m.sender_id = u.id 
        WHERE m.receiver_id = ? AND (m.sender_id = ? OR m.receiver_id = ?)
        ORDER BY m.created_at DESC
    ");
    
    $stmt->execute([$profile_id, $viewer_id, $viewer_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>