<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Utils\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\ProfileController;
use App\Controllers\AssignmentController;
use App\Controllers\MessageController;
use App\Controllers\ChallengeController;

$router = new Router($pdo);

// Home
$router->get('/', [HomeController::class, 'index']);

// Authentication
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Users
$router->get('/users', [UserController::class, 'list']);
$router->get('/manage-students', [UserController::class, 'manageForm']);
$router->post('/manage-students', [UserController::class, 'addStudent']);
$router->get('/edit-student', [UserController::class, 'editForm']);
$router->post('/edit-student', [UserController::class, 'editStudent']);
$router->post('/delete-student', [UserController::class, 'deleteStudent']);

// Profile
$router->get('/profile', [ProfileController::class, 'show']);
$router->post('/profile', [ProfileController::class, 'update']);

// Assignments 
$router->get('/assignments', [AssignmentController::class, 'list']);
$router->post('/assignments', [AssignmentController::class, 'upload']);
$router->get('/assignment', [AssignmentController::class, 'detail']);
$router->post('/assignment', [AssignmentController::class, 'submit']);
$router->get('/assignment-download', [AssignmentController::class, 'downloadAssignment']);
$router->get('/submission-download', [AssignmentController::class, 'downloadSubmission']);

// Messages
$router->post('/messages', [MessageController::class, 'handle']);

// Challenges
$router->get('/challenges', [ChallengeController::class, 'list']);
$router->post('/challenges', [ChallengeController::class, 'handle']);
$router->pattern('GET', '#^/challenge/(?P<id>\d+)/(?P<name>.+)$#', [ChallengeController::class, 'download']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
?>
