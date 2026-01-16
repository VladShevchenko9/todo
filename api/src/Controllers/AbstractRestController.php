<?php

namespace App\Controllers;

use App\Repositories\AbstractRepository;
use App\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class AbstractRestController extends AbstractController
{
    /** @var string */
    protected string $modelName;

    /**
     * Request validation rules for method store
     *
     * @var array
     */
    protected array $storeRules = [];

    /**
     * Request validation rules for method update
     *
     * @var array
     */
    protected array $updateRules = [];

    public function __construct(AbstractRepository $repo, Validator $validator)
    {
        parent::__construct($repo, $validator);
        $this->modelName = $this->repo->getModelName();
    }

    public function index(): void
    {
        $models = $this->repo->findAllModels();

        foreach ($models as $key => $model) {
            $models[$key] = $model->toArray();
        }

        $this->json($models);
    }

    /**
     * @param string $id
     */
    public function show(string $id): void
    {
        $model = $this->repo->findOne((int)$id);

        if (!$model) {
            $this->json(['error' => $this->modelName . ' not found'], 404);
            return;
        }

        $modelData = $model->toArray();
        $this->json($modelData);
    }

    public function store(): void
    {
        $inputData = $this->jsonData();

        try {
            $result = $this->validator->validate($inputData, $this->storeRules);

        } catch (ValidationException $e) {
            $this->json(['errors' => $e->errors()], 422);
            return;
        }

        $model = $this->makeModel($result);
        $created = $this->repo->create($model);

        $this->json($created->toArray(), 201);
    }

    /**
     * @param string $id
     */
    public function update(string $id): void
    {
        $existingModel = $this->repo->findOne((int)$id);

        if (!$existingModel) {
            $this->json(['error' => $this->modelName . ' not found'], 404);
            return;
        }

        $inputData = $this->jsonData();

        try {
            $result = $this->validator->validate($inputData, $this->updateRules);
        } catch (ValidationException $e) {
            $this->json(['errors' => $e->errors()], 422);
            return;
        }

        $existingModel->fill($result);

        $updatedModel = $this->repo->update($existingModel);

        $this->json($updatedModel->toArray());
    }

    /**
     * @param string $id
     */
    public function destroy(string $id): void
    {
        $deleted = $this->repo->deleteById((int)$id);

        if (!$deleted) {
            $this->json(['error' => $this->modelName . ' not found'], 404);
            return;
        }

        $this->json(['message' => $this->modelName . ' deleted']);
    }
}
