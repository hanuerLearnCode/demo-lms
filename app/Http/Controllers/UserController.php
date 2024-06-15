<?php

namespace App\Http\Controllers;

use App\Contract\Users\UserRepository;
use App\Contract\Users\UserService;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    //

    protected UserRepository $userService;

    public function __construct(UserRepository $userService)
    {
        $this->userService = $userService;
    }

    public function listUsers(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->userService->getAll();
        return UserResource::collection($users);
    }

    public function listStudents()
    {
        $students = $this->userService->getAll();
        return UserResource::collection($students);
    }

    public function findById(int $id): UserResource
    {
        $user = $this->userService->getById($id);
        return new UserResource($user);
    }

}
