<?php

namespace App\Contract\Users;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{

    public function getAll();

    public function getById(int $id);

    public function create(array $data = []);

    public function update(User $user, array $data = []);

    public function delete(User $user);
}
