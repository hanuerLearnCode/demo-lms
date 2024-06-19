<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
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

    public function getAll()
    {
        return UserResource::collection($this->userService->getAll());
    }

    public function getById(int $id)
    {
        $user = $this->userService->getById($id);
        if ($user) {
            return new UserResource($user);
        }

        return response()->json("Couldn't find the target user!", 404);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at') // Exclude soft-deleted users
            ],
            'password' => 'required|string|min:8|confirmed',
        ];

        $validated = $this->validateUserDataFromRequest($request, $rules);

        // if the result is a json file
        if (!is_array($validated))
            return $validated;

        // if it is an array
        $data = $validated;

        $data['password'] = Hash::make($data['password']);

        try {
            // create new user
            $this->userService->create($data);

            // response
            return response()->json("New user created!");

        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json("Something went wrong, couldn't create user!", 500);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at') // Exclude soft-deleted users
            ],
        ];

        $validated = $this->validateUserDataFromRequest($request, $rules);

        // if the result is a json file
        if (!is_array($validated))
            return $validated;

        // if it is an array
        $data = $validated;

        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);

        try {
            // update user infor
            $user = $this->userService->getById($id);

            if (!$user)
                return response()->json("Couldn't find the target user!");

            $this->userService->update($user, $data);

            return response()->json("User updated!");
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json("Something went wrong, couldn't update user!");
        }
    }

    public function delete(int $id)
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

    /**
     * @throws \Exception
     */
    public function validateUserDataFromRequest(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return json_encode($validator->errors()); // return a json if fail
        }

        return $validator->validated(); // Return validated data if successful
    }
}
