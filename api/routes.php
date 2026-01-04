<?php

use App\Core\Router;
use App\Controllers\TaskController;
use App\Infrastructure\Container\Container;

$container = new Container();

require __DIR__ . '/src/Infrastructure/Container/bindings.php';

$router = new Router($container);

$router->get('/tasks', [TaskController::class, 'index']);
$router->post('/tasks', [TaskController::class, 'store']);
$router->get('/tasks/{id}', [TaskController::class, 'show']);
$router->put('/tasks/{id}', [TaskController::class, 'update']);
$router->delete('/tasks/{id}', [TaskController::class, 'destroy']);

return $router;
