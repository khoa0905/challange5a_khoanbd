<?php

namespace App\Controllers;

use PDO;

class HomeController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(): void
    {
        $pageTitle = 'Home';
        include __DIR__ . '/../views/index.php';
    }
}
