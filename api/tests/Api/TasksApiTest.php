<?php

//.\vendor\bin\phpunit .\tests\Api\TasksApiTest.php
//@todo: Set validation messages
namespace Tests\Api;

use App\Models\Task;
use ReflectionException;
use Tests\Factories\TaskFactory;
use Tests\Factories\TestFactoryInterface;

class TasksApiTest extends AbstractApiTestCase
{
    private const FIRST_TASK = [
        'id' => 1,
        'user_id' => 1,
        'title' => 'Test title 1',
        'description' => 'Test description 1',
        'priority' => 3,
        'status' => false,
    ];

    private const LAST_TASK = [
        'id' => 3,
        'user_id' => 1,
        'title' => 'Title 3',
        'description' => 'Description 3',
        'priority' => 3,
        'status' => false,
    ];

    public function testIndex(): void
    {
        $totalTasks = 3;
        $this->get('/tasks');
        $this->assertEquals(200, $this->statusCode);
        $this->assertCount($totalTasks, $this->responseBody);
        $firstTask = $this->responseBody[0];
        $this->assertArrayHasKey('created_at', $firstTask);
        unset($firstTask['created_at']);
        $this->assertEquals(self::FIRST_TASK, $firstTask);

        $lastTask = $this->responseBody[$totalTasks - 1];
        $this->assertArrayHasKey('created_at', $lastTask);
        unset($lastTask['created_at']);
        $this->assertEquals(self::LAST_TASK, $lastTask);
    }

    public function testShow(): void
    {
        $taskId = 1;
        $this->get('/tasks/' . $taskId);
        $this->assertEquals(200, $this->statusCode);
        $this->assertArrayHasKey('created_at', $this->responseBody);
        unset($this->responseBody['created_at']);
        $this->assertEquals(self::FIRST_TASK, $this->responseBody);
    }

    public function testShowIfNotFound(): void
    {
        $taskId = -1;
        $expectedResponse = ['error' => 'Task not found'];
        $this->get('/tasks/' . $taskId);
        $this->assertEquals(404, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testStore(): void
    {
        $task = [
            'title' => 'Test Title',
            'description' => 'Test description',
            'status' => true,
            'user_id' => 1,
            'priority' => 3,
        ];

        $this->post('/tasks', $task);
        $this->assertEquals(201, $this->statusCode);
        $this->assertArrayHasKey('id', $this->responseBody);
        $this->assertIsInt($this->responseBody['id']);
        $this->assertArrayHasKey('created_at', $this->responseBody);
        $this->assertIsString($this->responseBody['created_at']);

        $id = $this->responseBody['id'];

        unset($this->responseBody['id']);
        unset($this->responseBody['created_at']);

        $this->assertEquals($task, $this->responseBody);

        $this->factory->delete($id);
    }

    public function testStoreIfInvalidData(): void
    {
        $task = [
            'title' => 1,
            'description' => 1,
            'status' => 'true',
            'user_id' => 'Test Title',
            'priority' => 'Test Title',
        ];

        $expectedResponse = [
            'errors' => [
                'title' => ['validation.string'],
                'description' => ['validation.string'],
                'status' => ['validation.boolean'],
                'user_id' => ['validation.integer'],
                'priority' => ['validation.integer', 'validation.between.numeric'],
            ]
        ];

        $this->post('/tasks', $task);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testStoreIfMissingData(): void
    {
        $task = [];
        $expectedResponse = [
            'errors' => [
                'title' => ['validation.required'],
                'description' => ['validation.required'],
                'user_id' => ['validation.required'],
                'priority' => ['validation.required'],
            ]
        ];

        $this->post('/tasks', $task);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testUpdate(): void
    {
        /** @var Task $task */
        $task = $this->factory->create();
        $id = $task->id;
        $task = [
            'title' => 'Test Title',
            'description' => 'Test description',
            'status' => true,
            'user_id' => 1,
            'priority' => 1,
        ];

        $this->put('/tasks/' . $id, $task);
        $this->assertEquals(200, $this->statusCode);
        $this->assertArrayHasKey('id', $this->responseBody);
        $this->assertIsInt($this->responseBody['id']);
        $this->assertArrayHasKey('created_at', $this->responseBody);
        $this->assertIsString($this->responseBody['created_at']);

        unset($this->responseBody['id']);
        unset($this->responseBody['created_at']);

        $this->assertEquals($task, $this->responseBody);
    }

    public function testUpdateIfNotFound(): void
    {
        $id = -1;
        $expectedResponse = [
            'error' => 'Task not found'
        ];

        $task = [];

        $this->put('/tasks/' . $id, $task);
        $this->assertEquals(404, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testUpdateIfNotValid(): void
    {
        $task = $this->factory->create();
        $id = $task->id;

        $task = [
            'title' => 1,
            'description' => 1,
            'status' => 'true',
            'user_id' => 'Test Title',
            'priority' => 'Test Title',
        ];

        $expectedResponse = [
            'errors' => [
                'title' => ['validation.string'],
                'description' => ['validation.string'],
                'status' => ['validation.boolean'],
                'user_id' => ['validation.integer'],
                'priority' => ['validation.integer', 'validation.between.numeric'],
            ]
        ];

        $this->put('/tasks/' . $id, $task);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testDestroy(): void
    {
        $task = $this->factory->create();
        $this->delete('/tasks/' . $task->id);
        $this->assertEquals(200, $this->statusCode);
    }

    public function testDestroyIfNotFound(): void
    {
        $id = -1;
        $this->delete('/tasks/' . $id);
        $expectedResponse = [
            'error' => 'Task not found'
        ];
        $this->assertEquals(404, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    /**
     * @return TestFactoryInterface
     * @throws ReflectionException
     */
    protected function getFactory(): TestFactoryInterface
    {
        /** @var TaskFactory $factory */
        $factory = self::$container->get(TaskFactory::class);
        return $factory;
    }
}
