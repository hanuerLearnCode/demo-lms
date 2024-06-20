<?php

namespace App\Service\User;

use App\Contract\Users\UserInterface;
use App\Contract\Users\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService implements UserRepositoryInterface
{

    public function getAll()
    {
        // remove get() to use paginate()
        return User::with('userRole');
    }

    public function getById(int $id)
    {
        return User::with('role')->find($id);
    }

    public function create(array $data = [])
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $data = $validator->validated();

        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function update(UserInterface $user, array $data = [])
    {

        // validate email
        $validator = Validator::make($data, [
            'name' => '',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => '',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $data = $validator->validated();

        // if user want to change password
        if (isset($password)) $data['password'] = Hash::make($password);

        return $user->update($data);
    }

    public function delete(UserInterface $user)
    {
        return $user->delete();
    }
}
