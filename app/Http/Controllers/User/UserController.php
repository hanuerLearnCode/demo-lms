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
        $user = $this->userService->create($data);
        // create his/her role
        $userRole = [
            'user_id' => $user->id,
            'role_id' => Role::STUDENT_ROLE_ID,
        ];
        $this->userRoleService->create($userRole);
        return response()->json("New user created!");
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        // update user infor
        $user = $this->userService->getById($id);
        $this->userService->update($user, $data);
        // update his/her role (if change)
        $user_role_id = $user->userRole->id;
        $userRole = $this->userRoleService->getById($user_role_id);
        $userRoleData = [
            'user_id' => $user->id,
            'role_id' => Role::STUDENT_ROLE_ID, // this could be something like $request->user_role
        ];
        $this->userRoleService->update($userRole, $userRoleData);

        return response()->json("User updated!");
    }

    public function delete(int $id)
    {
        $user = $this->userService->getById($id);
        $user_role_id = $user->userRole->id;
        $userRole = $this->userRoleService->getById($user_role_id);
        // delete user
        $this->userService->delete($user);
        // delete his/her role
        $this->userRoleService->delete($userRole);
        return response()->json("User deleted!");
    }
}
