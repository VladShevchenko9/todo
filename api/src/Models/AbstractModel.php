<?php

namespace App\Models;

use function Carbon\isEmpty;
use function property_exists;

abstract class AbstractModel
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    /**
     * @param array<string,mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
        $this->setCreatedAt();
    }

    /**
     * @param array<string,mixed> $data
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, (string)$key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }

    /**
     * @param array $without
     * @return array
     */
    public function toArray(array $without = []): array
    {
        $array = get_object_vars($this);

        foreach ($without as $propertyName) {
            unset($array[$propertyName]);
        }

        return $array;
    }

    private function setCreatedAt(): void
    {
        if (property_exists($this, 'created_at')) {
            $this->created_at = date('Y-m-d H:i:s');
        }
    }

    /**
     * @return string
     */
    public static function getModelName(): string
    {
        return basename(str_replace('\\', '/', static::class));
    }

    /**
     * @return int
     */
    public function getPrimaryKeyValue(): int
    {
        return $this->{static::$primaryKey};
    }

    /**
     * @return string
     */
    public static function getPrimaryKeyName(): string
    {
        return static::$primaryKey;
    }

    /**
     * @return string
     */
    public static function getTable(): string
    {
        return static::$table;
    }
}
