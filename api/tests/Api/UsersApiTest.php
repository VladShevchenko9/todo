<?php

//.\vendor\bin\phpunit .\tests\Api\UsersApiTest.php

namespace Tests\Api;

use ReflectionException;
use Tests\Factories\UserFactory;
use Tests\Factories\TestFactoryInterface;

class UsersApiTest extends AbstractApiTestCase
{
    private const FIRST_USER = [
        'id' => 1,
        'username' => 'Vlad',
        'email' => 'Vlad123@gmail.com',
    ];

    public function testStore(): void
    {
        $password = '123123123';

        $data = [
            'username' => 'NeVlad',
            'password' => $password,
            'email' => 'vlad@gmail.com',
        ];

        $this->post('/registration', $data);
        $this->assertEquals(201, $this->statusCode);
        $this->assertArrayHasKey('id', $this->responseBody);
        $this->assertIsInt($this->responseBody['id']);
        $id = $this->responseBody['id'];

        $this->assertArrayHasKey('created_at', $this->responseBody);
        $this->assertIsString($this->responseBody['created_at']);

        unset($this->responseBody['id']);
        unset($this->responseBody['created_at']);

        $this->assertEquals('NeVlad', $this->responseBody['username']);

        $isValidPassword = password_verify($password, $this->responseBody['password']);
        $this->assertTrue($isValidPassword);

        $this->assertEquals('vlad@gmail.com', $this->responseBody['password']);

        $this->factory->delete($id);
    }

    public function testStoreIfInvalidData(): void
    {
        $data = [
            'username' => 1,
            'password' => 1,
            'email' => 1,
        ];

        $expectedResponse = [
            'errors' => [
                'username' => ['validation.string', 'validation.min.string'],
                'password' => ['validation.string', 'validation.min.string'],
                'email' => ['validation.string', 'validation.email'],
            ]
        ];

        $this->post('/registration', $data);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testStoreIfMissingData(): void
    {
        $user = [];
        $expectedResponse = [
            'errors' => [
                'username' => ['validation.required'],
                'password' => ['validation.required'],
                'email' => ['validation.required'],
            ]
        ];

        $this->post('/registration', $user);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testUniqueUsername(): void
    {
        $data = [
            'username' => 'Vlad',
            'password' => 'password123',
            'email' => 'Vlad@gmail.com',
        ];

        $expectedResponse = [
            'errors' => [
                'username' => ['validation.unique'],
            ]
        ];

        $this->post('/registration', $data);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testUniqueEmail(): void
    {
        $data = [
            'username' => 'Vlad123',
            'password' => 'password123',
            'email' => 'Vlad123@gmail.com',
        ];

        $expectedResponse = [
            'errors' => [
                'email' => ['validation.unique'],
            ]
        ];

        $this->post('/registration', $data);
        $this->assertEquals(422, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    public function testShow(): void
    {
        $userId = 1;
        $this->get('/users/' . $userId);
        $this->assertEquals(200, $this->statusCode);
        $this->assertArrayHasKey('created_at', $this->responseBody);

        unset($this->responseBody['password']);
        unset($this->responseBody['created_at']);

        $this->assertEquals(self::FIRST_USER, $this->responseBody);
    }

    public function testShowIfNotFound(): void
    {
        $userId = -1;
        $expectedResponse = ['error' => 'User not found'];
        $this->get('/users/' . $userId);
        $this->assertEquals(404, $this->statusCode);
        $this->assertEquals($expectedResponse, $this->responseBody);
    }

    /**
     * @return TestFactoryInterface
     * @throws ReflectionException
     */
    protected function getFactory(): TestFactoryInterface
    {
        /** @var UserFactory $factory */
        $factory = self::$container->get(UserFactory::class);
        return $factory;
    }
}
