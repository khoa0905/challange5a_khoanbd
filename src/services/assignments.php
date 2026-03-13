<?php
function add_assignment($pdo, $title, $description, $file_path, $teacher_id) {
    $stmt = $pdo->prepare("INSERT INTO assignments (title, description, teacher_id, file_path) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$title, $description, $teacher_id, $file_path]);
}

function get_assignment_by_id($pdo, $id) {
    $stmt = $pdo->prepare("
        SELECT a.id, a.title, a.description, a.file_path, a.created_at, u.full_name as teacher_name
        FROM assignments a
        JOIN users u ON a.teacher_id = u.id
        WHERE a.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_all_assignments($pdo) {
    $stmt = $pdo->query("
        SELECT a.id, a.title, a.description, a.file_path, a.created_at, u.full_name as teacher_name 
        FROM assignments a 
        JOIN users u ON a.teacher_id = u.id 
        ORDER BY a.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>