<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\TeacherResource;
use App\Models\Role;
use App\Models\User;
use App\Service\User\TeacherService;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    //

    protected UserService $userService;
    protected TeacherService $teacherService;
    protected UserRoleService $userRoleService;

    public function __construct(UserService $userService, TeacherService $teacherService, UserRoleService $userRoleService)
    {
        $this->userService = $userService;
        $this->teacherService = $teacherService;
        $this->userRoleService = $userRoleService;
    }

    public function index()
    {
        $teachers = $this->teacherService->getAll();
        return TeacherResource::collection($teachers);
    }

    public function show(int $id)
    {
        if ($this->teacherService->getById($id))
            return new TeacherResource($this->teacherService->getById($id));

        return response()->json("Can not find the target teacher!");
    }

    public function store(Request $request)
    {
        $data = $request->all();

        try {

            // create new user
            $user = $this->userService->create($data);

            // if user infor is invalid
            if (!($user instanceof User))
                return json_decode($user);

            // save to students table
            $teacher_infor = [
                'user_id' => $user->id,
                'faculty_id' => $data['faculty_id'],
                'salary' => $data['salary'],
                'experience' => $data['experience'],
            ];
            $this->teacherService->create($teacher_infor);

            // save to users_roles table
            $role_data = [
                'user_id' => $user->id,
                'role_id' => Role::TEACHER_ROLE_ID,
            ];
            $this->userRoleService->create($role_data);

            // return msg
            return response()->json('New teacher created');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json("Something went wrong, couldn't create user!");
        }

    }

    public function update(Request $request, int $id)
    {
        $data = $request->all();
        try {
            $teacher = $this->teacherService->getById($id);
            if (!$teacher) return response()->json("Couldn't find the target teacher!");
            $user = $this->userService->getById($teacher->user->id);
            if (!$user) return response()->json("Couldn't find the target user!");


            // update user infor
            $update = $this->userService->update($user, $data);

            // if new infor is not valid
            if ($update !== true) {
                return $update;
            }

            // update teacher infor
            $this->teacherService->update($teacher, $data);


            return response()->json('Teacher updated!');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json('Something went wrong, can not update this teacher!');
        }

    }

    public function destroy(int $id)
    {
        $teacher = $this->teacherService->getById($id);
        if (!$teacher) return response()->json("Couldn't find the target teacher!");

        $user = $this->userService->getById($teacher->user->id);
        if (!$user) return response()->json("Couldn't find the target user!");

        try {
            // delete from students table
            $this->teacherService->delete($teacher);

            // delete from users_roles table
            $this->userRoleService->delete($user->userRole);

            // delete from users table
            $this->userService->delete($user);

            return response()->json("Teacher deleted!");
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return "Something went wrong, can not delete the target teacher!";
        }
    }
}
