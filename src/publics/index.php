<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Utils\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\ProfileController;
use App\Controllers\AssignmentController;
use App\Controllers\MessageController;

$router = new Router($pdo);

// Home
$router->get('/', [HomeController::class, 'index']);

// Authentication
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Users
$router->get('/users', [UserController::class, 'list']);
$router->get('/manage-students', [UserController::class, 'manageForm']);
$router->post('/manage-students', [UserController::class, 'addStudent']);

// Profile
$router->get('/profile', [ProfileController::class, 'show']);
$router->post('/profile', [ProfileController::class, 'update']);

// Assignments 
$router->get('/assignments', [AssignmentController::class, 'list']);
$router->post('/assignments', [AssignmentController::class, 'upload']);
$router->get('/assignment', [AssignmentController::class, 'detail']);
$router->post('/assignment', [AssignmentController::class, 'submit']);

// Messages
$router->post('/messages', [MessageController::class, 'handle']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
?>