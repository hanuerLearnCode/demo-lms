<?php

namespace App\Http\Controllers\User;

use App\Contract\Users\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Service\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getAll()
    {
        $users = $this->userService->getAll();
        return $users;
    }

    public function getById(int $id)
    {
        $user = $this->userService->getById($id);
        return $user;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $this->userService->create($data);
        return response()->json("New user created!");
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = $this->userService->getById($id);
        $this->userService->update($user, $data);
        return response()->json("User updated!");
    }

    public function delete(int $id)
    {
        $user = $this->userService->getById($id);
        $this->userService->delete($user);
        return response()->json("User deleted!");
    }
}
