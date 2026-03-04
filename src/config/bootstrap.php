<?php
session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relative_class = substr($class, strlen($prefix));

    $relative_class = lcfirst($relative_class);

    $file = __DIR__ . '/../' . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// ── Function-based files (not namespaced) ───────────────────────────────
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../utils/middleware.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../services/auth.php';
require_once __DIR__ . '/../services/users.php';
require_once __DIR__ . '/../services/assignments.php';
require_once __DIR__ . '/../services/submissions.php';
require_once __DIR__ . '/../services/messages.php';

// ── Database connection ─────────────────────────────────────────────────
$pdo = get_db_connection();
