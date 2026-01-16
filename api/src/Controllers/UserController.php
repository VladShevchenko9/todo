<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends AbstractController
{
    /** @var array */
    protected array $storeRules = [
        'username' => 'required|string|unique:users|min:3|max:255',
        'password' => 'required|string|min:8|max:255',
        'email' => 'required|string|email|unique:users|max:255',
    ];

    public function __construct(UserRepository $repo, Validator $validator)
    {
        parent::__construct($repo, $validator);
    }

    public function registration(): void
    {
        $inputData = $this->jsonData();
        try {
            $result = $this->validator->validate($inputData, $this->storeRules);
        } catch (ValidationException $e) {
            $this->json(['errors' => $e->errors()], 422);
            return;
        }

        $model = $this->makeModel($result);
        $model->password = password_hash($model->password, PASSWORD_DEFAULT);
        $created = $this->repo->create($model);

        $this->json($created->toArray(), 201);
    }

    /**
     * @param string $id
     */
    public function show(string $id): void
    {
        $model = $this->repo->findOne((int)$id);

        if (!$model) {
            $this->json(['error' => $this->repo->getModelName() . ' not found'], 404);
            return;
        }

        $modelData = $model->toArray();
        $this->json($modelData);
    }
}
