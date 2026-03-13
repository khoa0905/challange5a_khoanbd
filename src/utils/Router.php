<?php

namespace App\Utils;

class Router
{
    private array $routes = [];
    private array $patternRoutes = [];
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

    public function pattern(string $method, string $regex, array $handler): void
    {
        $this->patternRoutes[] = [
            'method'  => $method,
            'regex'   => $regex,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if ($method === 'POST') {
            verify_csrf_token();
        }

        $handler = $this->routes[$method][$uri] ?? null;

        if ($handler) {
            [$class, $action] = $handler;
            $controller = new $class($this->pdo);
            $controller->$action();
            return;
        }

        foreach ($this->patternRoutes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['regex'], $uri, $matches)) {
                [$class, $action] = $route['handler'];
                $controller = new $class($this->pdo);
                $controller->$action($matches);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 — Page Not Found</h1>';
    }
}
?>