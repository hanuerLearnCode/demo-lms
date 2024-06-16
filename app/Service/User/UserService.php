<?php

namespace App\Service\User;

use App\Contract\Users\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Client\Request;

class UserService implements UserRepositoryInterface
{

    public function getAll()
    {
        // TODO: Implement getAll() method.
        return User::all();
    }

    public function getById(int $id)
    {
        // TODO: Implement getById() method.
        return User::find($id);
    }

    public function create(array $data = [])
    {
        return User::create($data);
    }

    public function update(User $user, array $data = [])
    {
        // TODO: Implement update() method.
        return $user->update($data);
    }

    public function delete(User $user)
    {
        // TODO: Implement delete() method.
        return $user->delete();
    }
}
