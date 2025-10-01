<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

use App\Helpers\Router;

$router = new Router(require BASE_PATH . '/config/routes.php');

$path = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$response = $router->dispatch($method, $path);

if (is_string($response)) {
    echo $response;
}