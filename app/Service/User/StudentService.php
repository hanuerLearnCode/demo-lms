<?php

namespace App\Service\User;

use App\Contract\Users\UserRepositoryInterface;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentService implements UserRepositoryInterface
{

    public function getAll()
    {
        return Student::with('user')->with('officeClass')->with('faculty')->get();
    }

    public
    function getById(int $id)
    {

    }

    public
    function create(array $data = [])
    {

    }

    public
    function update(User $user, array $data = [])
    {

    }

    public
    function delete(User $user)
    {

    }
}
