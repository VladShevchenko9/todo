<?php

namespace App\Core;

use App\Infrastructure\Container\Container;
use ReflectionException;

class Router
{
    /** @var array<int, array{method:string,regex:string,handler:array}> */
    private array $routes = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $pattern
     * @param array $handler
     */
    public function get(string $pattern, array $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param array $handler
     */
    public function post(string $pattern, array $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param array $handler
     */
    public function put(string $pattern, array $handler): void
    {
        $this->addRoute('PUT', $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param array $handler
     */
    public function patch(string $pattern, array $handler): void
    {
        $this->addRoute('PATCH', $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param array $handler
     */
    public function delete(string $pattern, array $handler): void
    {
        $this->addRoute('DELETE', $pattern, $handler);
    }

    /**
     * @param string $method
     * @param string $pattern
     * @param array $handler
     */
    private function addRoute(string $method, string $pattern, array $handler): void
    {
        $regex = preg_replace('#{[^/]+}#', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        $this->routes[] = [
            'method' => $method,
            'regex' => $regex,
            'handler' => $handler,
        ];
    }

    /**
     * @param string $uri
     * @param string $method
     * @throws ReflectionException
     */
    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if ($path !== null && preg_match($route['regex'], $path, $matches)) {
                array_shift($matches);

                $handler = $route['handler'];

                if (is_array($handler) && count($handler) === 2) {
                    [$class, $action] = $handler;
                    $controller = $this->container->get($class);

                    $controller->$action(...$matches);
                    return;
                }
            }
        }

        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Route not found'], JSON_UNESCAPED_UNICODE);
    }
}
