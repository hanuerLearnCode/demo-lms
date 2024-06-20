<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function index()
    {
        return UserResource::collection($this->userService->getAll());
    }

    public function show(int $id)
    {
        $user = $this->userService->getById($id);
        if ($user) {
            return new UserResource($user);
        }

        return response()->json("Couldn't find the target user!", 404);
    }

    public function store(Request $request)
    {

        $data = $request->all();

        $data['password'] = Hash::make($data['password']);

        try {
            // create new user
            $user = $this->userService->create($data);

            // if
            if (!($user instanceof User))
                return json_decode($user);

            // response
            return response()->json("New user created!");

        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json("Something went wrong, couldn't create user!", 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);

        try {

            $user = $this->userService->getById($id);

            if (!$user)
                return response()->json("Couldn't find the target user!");

            // update user infor
            $update = $this->userService->update($user, $data);

            if ($update !== true) {
                return $update;
            } else {
                return response()->json("User updated!");
            }


        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json("Something went wrong, couldn't update user!");
        }
    }

    public function destroy(int $id)
    {
        $user = $this->userService->getById($id);

        if (!$user)
            return response()->json("Couldn't find the target user!");

        try {
            // delete user
            $this->userService->delete($user);

            return response()->json("User deleted!");
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json("Something went wrong, couldn't delete user!");
        }
    }
}
