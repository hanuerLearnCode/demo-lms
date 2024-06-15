<?php

namespace App\Contract\Users;

use App\Models\Student;
use App\Models\User;

class StudentService implements UserRepository
{

    public function getAll()
    {
        // TODO: Implement getAll() method.
        return Student::all();
    }

    public function getById(int $user_id)
    {
        // TODO: Implement getById() method.
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function update(User $user, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(User $user)
    {
        // TODO: Implement delete() method.
    }
}
