<?php
function add_challenge($pdo, $teacher_id, $hint) {
    $stmt = $pdo->prepare("INSERT INTO challenges (teacher_id, hint) VALUES (?, ?)");
    $stmt->execute([$teacher_id, $hint]);
    return $pdo->lastInsertId();
}

function get_all_challenges($pdo) {
    $stmt = $pdo->query("
        SELECT c.id, c.hint, c.created_at, u.full_name AS teacher_name
        FROM challenges c
        JOIN users u ON c.teacher_id = u.id
        ORDER BY c.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_challenge_by_id($pdo, $id) {
    $stmt = $pdo->prepare("
        SELECT c.id, c.hint, c.created_at, u.full_name AS teacher_name
        FROM challenges c
        JOIN users u ON c.teacher_id = u.id
        WHERE c.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
