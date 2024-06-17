<?php

namespace App\Service\User;

use App\Contract\Users\UserInterface;
use App\Contract\Users\UserRepositoryInterface;
use App\Models\Student;

class StudentService implements UserRepositoryInterface
{

    public function getAll()
    {
        return Student::with('user')->with('officeClass')->with('faculty')->get();
    }

    public function getById(int $id)
    {
        return Student::with('user')->with('officeClass')->with('faculty')->find($id);
    }

    public function create(array $data = [])
    {
        return Student::create($data);
    }

    public function update(UserInterface $student, array $data = [])
    {
        return $student->update($data);
    }

    public function delete(UserInterface $student)
    {
        return $student->delete();
    }
}
