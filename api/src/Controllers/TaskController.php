<?php

namespace App\Controllers;

use App\Repositories\TaskRepository;
use App\Validation\Validator;

class TaskController extends AbstractRestController
{
    //@todo: Improve validation rules(min/max values, existing id`s). Consider creating classes for the rules.
    /** @var array */
    protected array $storeRules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:65535',
        'status' => 'bool',
        'user_id' => 'required|integer|exists:users,id',
        'priority' => 'required|integer|between:1,5',
    ];

    /** @var array */
    protected array $updateRules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:65535',
        'status' => 'bool',
        'user_id' => 'required|integer|exists:users,id',
        'priority' => 'required|integer|between:1,5',
    ];

    public function __construct(TaskRepository $repo, Validator $validator)
    {
        parent::__construct($repo, $validator);
    }
}
