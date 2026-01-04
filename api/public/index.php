<?php

//php -S localhost:8001 -t public

use App\Core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var Router $router */
$router = require_once __DIR__ . '/../routes.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

try {
    $router->dispatch($uri, $method);
} catch (ReflectionException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Internal server error'], JSON_UNESCAPED_UNICODE);
}
