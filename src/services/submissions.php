<?php
function add_submission($pdo, $assignment_id, $student_id, $file_path) {
    $stmt = $pdo->prepare("INSERT INTO submissions (assignment_id, student_id, file_path) VALUES (?, ?, ?)");
    return $stmt->execute([$assignment_id, $student_id, $file_path]);
}

function get_submissions_by_assignment($pdo, $assignment_id) {
    $stmt = $pdo->prepare("
        SELECT s.id, s.file_path, s.submitted_at, u.username, u.full_name 
        FROM submissions s
        JOIN users u ON s.student_id = u.id
        WHERE s.assignment_id = ?
        ORDER BY s.submitted_at DESC
    ");
    $stmt->execute([$assignment_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_student_submission($pdo, $assignment_id, $student_id) {
    $stmt = $pdo->prepare("
        SELECT id, file_path, submitted_at 
        FROM submissions 
        WHERE assignment_id = ? AND student_id = ?
    ");
    $stmt->execute([$assignment_id, $student_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>