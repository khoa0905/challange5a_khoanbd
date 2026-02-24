<?php
session_start();
require_once "../config/db.php";
require_once "../services/users.php";
require_once "../utils/middleware.php";

require_auth();

$pdo = get_db_connection();
$users = get_all_users($pdo);

include '../views/users.php';
?>