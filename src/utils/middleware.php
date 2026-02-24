<?php
function require_auth() {
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit;
    }
}

function require_role($required_role) {
    require_auth();

    if ($_SESSION['role'] !== $required_role) {
        header('Location: index.php');
        exit;
    }
}
?>