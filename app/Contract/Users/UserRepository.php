<?php

namespace App\Contract\Users;

use App\Http\Resources\UserResource;
use App\Models\User;

interface UserRepository
{
    public function getAll();

    public function getById(int $user_id);

    public function create(array $data);

    public function update(User $user, array $data);

    public function delete(User $user);
}
