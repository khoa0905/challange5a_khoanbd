<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => !empty($_SERVER['HTTPS']),
    'httponly'  => true,
    'samesite' => 'Lax',
]);
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

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../utils/middleware.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../services/auth.php';
require_once __DIR__ . '/../services/users.php';
require_once __DIR__ . '/../services/assignments.php';
require_once __DIR__ . '/../services/submissions.php';
require_once __DIR__ . '/../services/messages.php';
require_once __DIR__ . '/../services/challenges.php';

$pdo = get_db_connection();
?>