<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $users = UserResource::collection($this->userService->getAll()->paginate(10));
        return view('users.index')->with([
            'users' => $users,
        ]);
    }

    public function show(int $id)
    {
        $user = $this->userService->getById($id);
        if ($user) {
            return new UserResource($user);
        }

        return response()->json("Couldn't find the target user!", 404);
    }

    public function create()
    {
        return view('users.add');
    }

    public function store(Request $request)
    {

        $data = $request->all();

        unset($data['_token']);

        try {
            // create new user
            $user = $this->userService->create($data);

            // if
            if (!($user instanceof User))
                return redirect()->back()->withErrors($user)->withInput();

            // response
            return redirect(route('students.create'))->with([
                'success' => 'New user created!',
            ]);

        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new user, please try again!',
            ]);
        }
    }

    public function edit(int $id)
    {
        $user = $this->userService->getById($id);
        return view('users.edit')->with([
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);

        try {

            $user = $this->userService->getById($id);

            if (!$user)
                return redirect()->back()->withErrors($user)->withInput();

            // update user infor
            $update = $this->userService->update($user, $data);

            if ($update !== true) {
                return redirect()->back()->withErrors($update)->withInput();
            } else {
                return redirect('/users')->with([
                    'success' => 'User has been updated!',
                ]);
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
