<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Student;
use App\Models\User;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

    // Regiter new user (?)
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

    public function search(Request $request)
    {
        $query = $request->input('query');

        // using laravel caching system to optimize search
        $users = Cache::remember('users_search' . $query, 3600, function () use ($query) {
            return User::join('users_roles', 'users.id', '=', 'users_roles.user_id')
                ->join('roles', 'users_roles.role_id', '=', 'roles.id')
                ->where(function ($q) use ($query) {
                    $q->where('users.name', 'like', "%$query%")
                        ->orWhere('roles.title', 'like', "%$query");
                })
                ->select('users.*')
                ->paginate(10); // optimize search with paginate
        });

        $count = 0;
        $html = '';

        if (count($users) <= 0) return '<tr><td colspan="7" class="px-6 py-4 text-center">No results for your search</td></tr>';

        foreach ($users as $user) {
            $count++;
            $html .= view('partials.user_row', compact('user', 'count'))->render();
        }
        return $html;
    }
}
