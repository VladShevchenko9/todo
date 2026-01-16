<?php

namespace Tests\Factories;

use App\Models\User;
use App\Repositories\UserRepository;

class UserFactory extends AbstractModelFactory
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param array $overrides
     * @return User
     */
    public function create(array $overrides = []): User
    {
        $data = array_merge([
            'username' => 'Fake Username ' . uniqid(),
            'password' => 'Fake password ' . uniqid(),
            'email' => 'Test@gmail.com ' . uniqid(),
        ], $overrides);

        $user = User::fromArray($data);

        /** @var User $user */
        $user = $this->repository->create($user);
        $this->track($user);

        return $user;
    }
}
