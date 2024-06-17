<?php

namespace App\Service\User;

use App\Contract\Users\UserInterface;
use App\Contract\Users\UserRepositoryInterface;
use App\Models\User;

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

    public function update(UserInterface $user, array $data = [])
    {
        return $user->update($data);
    }

    public function delete(UserInterface $user)
    {
        return $user->delete();
    }
}
