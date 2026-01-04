<?php

namespace Tests\Factories;

use App\Models\AbstractModel;

interface TestFactoryInterface
{
    /**
     * @param array $overrides
     * @return AbstractModel
     */
    public function create(array $overrides = []): AbstractModel;

    /**
     * @param int $id
     */
    public function delete(int $id): void;

    public function cleanup(): void;
}
