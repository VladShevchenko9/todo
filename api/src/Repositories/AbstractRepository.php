<?php

/** @noinspection SqlNoDataSourceInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection SqlResolveInspection */

namespace App\Repositories;

use App\Models\AbstractModel;
use PDO;
use RuntimeException;

abstract class AbstractRepository
{
    /** @var string */
    protected string $modelClass;

    /** @var PDO */
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param int $id
     * @return AbstractModel|null
     */
    public function findOne(int $id): ?AbstractModel
    {
        $table = $this->getModelTable();
        $primaryKeyName = $this->getModelPrimaryKeyName();

        $sql = "SELECT * FROM $table WHERE $primaryKeyName = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$primaryKeyName => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        /** @var AbstractModel $class */
        $class = $this->modelClass;
        return $class::fromArray($row);
    }

    /**
     * @return AbstractModel[]
     */
    public function findAllModels(): array
    {
        $table = $this->getModelTable();

        $sql = "SELECT * FROM $table";
        $rows = $this->pdo->query($sql)->fetchAll();

        /** @var AbstractModel $class */
        $class = $this->modelClass;
        return array_map(static fn($row) => $class::fromArray($row), $rows);
    }

    /**
     * @param AbstractModel $model
     * @return AbstractModel
     */
    public function create(AbstractModel $model): AbstractModel
    {
        $table = $this->getModelTable();
        $primaryKeyName = $this->getModelPrimaryKeyName();

        $data = $model->toArray([$primaryKeyName]);

        $columns = array_keys($data);
        $placeholders = array_map(static fn($col) => ':' . $col, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(',', $columns),
            implode(',', $placeholders)
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        $id = (int)$this->pdo->lastInsertId();
        $created = $this->findOne($id);

        if (!$created) {
            throw new RuntimeException("Failed to fetch newly created record with ID $id");
        }

        return $created;
    }

    /**
     * @param AbstractModel $model
     * @return AbstractModel
     */
    public function update(AbstractModel $model): AbstractModel
    {
        $table = $this->getModelTable();
        $primaryKeyName = $this->getModelPrimaryKeyName();

        $data = $model->toArray([$primaryKeyName]);

        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = :{$column}";
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :id',
            $table,
            implode(', ', $setParts),
            $primaryKeyName
        );

        $data[$primaryKeyName] = $model->getPrimaryKeyValue();

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $model;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        $table = $this->getModelTable();
        $primaryKeyName = $this->getModelPrimaryKeyName();

        $sql = "DELETE FROM $table WHERE $primaryKeyName = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$primaryKeyName => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        /** @var AbstractModel $modelClass */
        $modelClass = $this->getModelClass();
        return $modelClass::getModelName();
    }

    /**
     * @return string
     */
    protected function getModelTable(): string
    {
        /** @var AbstractModel $modelClass */
        $modelClass = $this->getModelClass();
        return $modelClass::getTable();
    }

    /**
     * @return string
     */
    protected function getModelPrimaryKeyName(): string
    {
        /** @var AbstractModel $modelClass */
        $modelClass = $this->getModelClass();
        return $modelClass::getPrimaryKeyName();
    }

}
