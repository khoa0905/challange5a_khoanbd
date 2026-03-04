<?php

namespace App\Utils;

class Router
{
    private array $routes = [];
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        // Strip query string and trailing slash (keep root "/")
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo '<h1>404 — Page Not Found</h1>';
            return;
        }

        [$class, $action] = $handler;
        $controller = new $class($this->pdo);
        $controller->$action();
    }
}
