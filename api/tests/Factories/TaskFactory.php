<?php

namespace Tests\Factories;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskFactory extends AbstractModelFactory
{
    public function __construct(TaskRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param array $overrides
     * @return Task
     */
    public function create(array $overrides = []): Task
    {
        $data = array_merge([
            'user_id' => 1,
            'title' => 'Fake task ' . uniqid(),
            'description' => 'Fake description',
            'priority' => 3,
            'status' => false,
        ], $overrides);

        $task = Task::fromArray($data);

        /** @var Task $task */
        $task = $this->repository->create($task);

        $this->track($task);

        return $task;
    }
}
