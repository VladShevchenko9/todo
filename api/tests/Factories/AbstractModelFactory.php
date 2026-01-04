<?php

namespace Tests\Factories;

use App\Models\AbstractModel;
use App\Repositories\AbstractRepository;

abstract class AbstractModelFactory implements TestFactoryInterface
{
    /** @var AbstractModel[] */
    protected array $created = [];

    /** @var AbstractRepository */
    protected AbstractRepository $repository;

    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    public function cleanup(): void
    {
        foreach ($this->created as $model) {
            $this->delete($model->getPrimaryKeyValue());
        }
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->repository->deleteById($id);
    }

    /**
     * @param AbstractModel $model
     */
    protected function track(AbstractModel $model): void
    {
        $this->created[] = $model;
    }
}
