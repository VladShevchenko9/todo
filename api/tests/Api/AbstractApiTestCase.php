<?php

namespace Tests\Api;

use App\Infrastructure\Container\Container;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Tests\Factories\TestFactoryInterface;

abstract class AbstractApiTestCase extends TestCase
{
    private const BASE_URI = 'http://localhost:8001/';

    protected static Client $client;
    protected static ?Container $container = null;

    protected int $statusCode;
    protected array $responseBody;
    protected TestFactoryInterface $factory;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            'base_uri' => self::BASE_URI,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        self::setContainer();
    }

    /**
     * @param string $uri
     */
    protected function get(string $uri): void
    {
        try {
            $response = self::$client->get($uri);
        } catch (GuzzleException $e) {
            $this->statusCode = -1;
            $this->responseBody = [];
            return;
        }

        $this->statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $this->responseBody = json_decode($body, true);
    }

    /**
     * @param string $uri
     */
    protected function delete(string $uri): void
    {
        try {
            $response = self::$client->delete($uri);
        } catch (GuzzleException $e) {
            $this->statusCode = -1;
            $this->responseBody = [];
            return;
        }

        $this->statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $this->responseBody = json_decode($body, true);
    }

    /**
     * @param string $uri
     * @param array $data
     */
    protected function post(string $uri, array $data): void
    {
        try {
            $response = self::$client->post($uri, [
                'json' => $data
            ]);
        } catch (GuzzleException $e) {
            $this->statusCode = -1;
            $this->responseBody = [];
            return;
        }

        $this->statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $this->responseBody = json_decode($body, true);
    }

    /**
     * @param string $uri
     * @param array $data
     */
    protected function put(string $uri, array $data): void
    {
        try {
            $response = self::$client->put($uri, [
                'json' => $data
            ]);
        } catch (GuzzleException $e) {
            $this->statusCode = -1;
            $this->responseBody = [];
            return;
        }

        $this->statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $this->responseBody = json_decode($body, true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = $this->getFactory();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->factory->cleanup();
    }

    private static function setContainer(): void
    {
        if (self::$container) {
            return;
        }

        $container = new Container();
        self::$container = $container;
        require_once __DIR__ . '/../../src/Infrastructure/Container/bindings.php';
    }

    /**
     * @return TestFactoryInterface
     */
    abstract protected function getFactory(): TestFactoryInterface;
}
