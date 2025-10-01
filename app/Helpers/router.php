<?php

namespace App\Helpers;

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\AdminOnlyMiddleware;

class Router
{
    private array $routes;

    private array $middlewareMap = [
        'auth' => AuthMiddleware::class,
        'csrf' => CsrfMiddleware::class,
        'role' => RoleMiddleware::class,
        'admin_only' => AdminOnlyMiddleware::class,
    ];

    public function __construct(array $routes)
    {
        $this->routes = array_map(function ($route) {
            return [
                'method' => strtoupper($route[0] ?? $route['method'] ?? 'GET'),
                'path' => $this->normalizePath($route[1] ?? $route['path'] ?? '/'),
                'handler' => $route[2] ?? $route['handler'] ?? null,
                'middleware' => $route[3] ?? $route['middleware'] ?? [],
            ];
        }, $routes);
    }

    public function dispatch(string $method, string $uri)
    {
        $method = strtoupper($method);
        $uri = $this->normalizePath($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if ($route['path'] !== $uri) {
                continue;
            }

            $middlewareList = is_array($route['middleware']) ? $route['middleware'] : [];
            foreach ($middlewareList as $definition) {
                [$name, $params] = $this->parseMiddleware($definition);
                if (!isset($this->middlewareMap[$name])) {
                    continue;
                }
                $class = $this->middlewareMap[$name];
                $middleware = new $class();
                $result = $middleware->handle($params);
                if ($result === false) {
                    return null;
                }
            }

            $handler = $route['handler'];
            if (is_callable($handler)) {
                return call_user_func($handler);
            }

            if (is_array($handler) && count($handler) === 2) {
                [$class, $action] = $handler;
                if (class_exists($class)) {
                    $controller = new $class();
                    if (method_exists($controller, $action)) {
                        return $controller->$action();
                    }
                }
            }
        }

        http_response_code(404);
        $viewPath = config('paths.views') . '/pages/404.php';
        if (file_exists($viewPath)) {
            $data = ['title' => 'Halaman Tidak Ditemukan'];
            extract($data);
            include $viewPath;
            return null;
        }

        echo '404 - Not Found';
        return null;
    }

    private function normalizePath(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }

    private function parseMiddleware($definition): array
    {
        if (is_string($definition) && str_contains($definition, ':')) {
            [$name, $paramString] = explode(':', $definition, 2);
            $params = array_filter(array_map('trim', explode(',', $paramString)));
            return [$name, $params];
        }

        if (is_string($definition)) {
            return [$definition, []];
        }

        if (is_array($definition) && isset($definition['name'])) {
            return [$definition['name'], $definition['params'] ?? []];
        }

        return [$definition, []];
    }
}
