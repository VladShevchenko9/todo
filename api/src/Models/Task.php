<?php

namespace App\Models;

class Task extends AbstractModel
{
    protected static string $table = 'tasks';

    public int $id;
    public int $user_id;
    public string $title;
    public ?string $description = null;
    public int $priority = 3;
    public bool $status = false;
    public string $created_at;
}
