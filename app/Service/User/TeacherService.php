<?php

namespace App\Service\User;

use App\Contract\Users\UserInterface;
use App\Contract\Users\UserRepositoryInterface;
use App\Models\Teacher;

class TeacherService implements UserRepositoryInterface
{
    public function getAll()
    {
        return Teacher::with('user')->with('faculty');
    }

    public function getById(int $id)
    {
        return Teacher::with('user')->with('faculty')->find($id);
    }

    public function create(array $data = [])
    {
        return Teacher::create($data);
    }

    public function update(UserInterface $teacher, array $data = [])
    {
        return $teacher->update($data);
    }

    public function delete(UserInterface $teacher)
    {
        return $teacher->delete();
    }
}
