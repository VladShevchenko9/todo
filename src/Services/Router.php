<?php

namespace Todo\Services;

class Router
{
    private const ROUTES = [
        '/todo/' => __DIR__ . '/../../templates/pages/login.php',
        '/todo/tasks' => __DIR__ . '/../../templates/pages/tasks.php',
        '/todo/registration' => __DIR__ . '/../../templates/pages/registration.php'
    ];

    /** @var array */
    public static array $queryParams = [];

    /**
     * @return void
     */
    public static function route(): void
    {
        $uri = self::getUri();

        if (array_key_exists($uri, self::ROUTES)) {
            require self::ROUTES[$uri];
        } else {
            self::abort();
        }
    }

    /**
     * @param int $code
     * @return void
     */
    private static function abort(int $code = 404): void
    {
        http_response_code($code);
        require __DIR__ . "/../../templates/pages/{$code}.php";
    }

    /**
     * @return string
     */
    private static function getUri(): string
    {
        $queryData = parse_url($_SERVER['REQUEST_URI']);
        self::$queryParams = [];

        if (array_key_exists('query', $queryData)) {
            parse_str($queryData['query'], self::$queryParams);
        }

        return $queryData['path'];
    }
}
