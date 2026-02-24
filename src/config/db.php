<?php
function get_db_connection() {
    $host = 'db';
    $db   = 'class_manager';
    $username = 'root';
    $password = 'rootpassword';

    $dsn = "mysql:host=$host;dbname=$db";
    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
?>