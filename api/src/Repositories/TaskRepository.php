<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository extends AbstractRepository
{
    /** @var string */
    protected string $modelClass = Task::class;
}
