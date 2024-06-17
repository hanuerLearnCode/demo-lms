<?php

namespace App\Http\Controllers\User;

use App\Contract\Users\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    protected UserService $userService;
    protected UserRoleService $userRoleService;

    public function __construct(UserService $userService, UserRoleService $userRoleService)
    {
        $this->userService = $userService;
        $this->userRoleService = $userRoleService;
    }

    public function getAll()
    {
        return UserResource::collection($this->userService->getAll());
    }

    public function getById(int $id)
    {
        $user = $this->userService->getById($id);
        return new UserResource($user);
    }

    public function create(Request $request)
    {
        $data = $request->all();
        // create new user
        $this->userService->create($data);
        return response()->json("New user created!");
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        // update user infor
        $user = $this->userService->getById($id);
        $this->userService->update($user, $data);
        return response()->json("User updated!");
    }

    public function delete(int $id)
    {
        $user = $this->userService->getById($id);
        // delete user
        $this->userService->delete($user);
        return response()->json("User deleted!");
    }
}
