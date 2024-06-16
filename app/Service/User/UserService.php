<?php

namespace App\Service\User;

use App\Contract\Users\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Client\Request;

class UserService implements UserRepositoryInterface
{

    public function getAll()
    {
        return User::with('userRole')->get();
    }

    public function getById(int $id)
    {
        return User::with('role')->find($id);
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
