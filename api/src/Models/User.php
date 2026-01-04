<?php

namespace App\Models;

class User extends AbstractModel
{
    protected static string $table = 'tasks';

    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public string $created_at;
}
