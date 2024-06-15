<?php

namespace App\Contract\Users;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService implements UserRepository
{

    public function getAll(): Collection
    {
        return User::all();
    }

    public function getById(int $user_id)
    {
        return User::find($user_id);
    }

    public function create(array $data)
    {
    }

    public function update(User $user, array $data)
    {
    }

    public function delete(User $user)
    {
    }
}
