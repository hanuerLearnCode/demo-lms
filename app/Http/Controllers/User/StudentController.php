<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\StudentResource;
use App\Models\Role;
use App\Models\User;
use App\Service\User\StudentService;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    //
    protected UserService $userService;
    protected StudentService $studentService;
    protected UserRoleService $userRoleService;

    public function __construct(UserService $userService, StudentService $studentService, UserRoleService $userRoleService)
    {
        $this->userService = $userService;
        $this->studentService = $studentService;
        $this->userRoleService = $userRoleService;
    }

    public function index()
    {
        $students = StudentResource::collection($this->studentService->getAll()->paginate(10));
        return view('students.index')->with([
            'students' => $students,
        ]);
    }

    public function show(int $id)
    {
        $student = $this->studentService->getById($id);

        if ($student)
            return new StudentResource($student);

        return response()->json("Couldn't find the target student!", 404);
    }

    public function create()
    {
        return view('students.add');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        try {

            // create new user
            $user = $this->userService->create($data);

            // if user infor is invalid
            if (!($user instanceof User))
                return redirect()->back()->withErrors($user)->withInput();

            // save to students table
            $student_infor = [
                'user_id' => $user->id,
                'office_class_id' => $data['office_class_id'],
                'faculty_id' => $data['faculty_id'],
            ];
            $this->studentService->create($student_infor);

            // save to users_roles table
            $role_data = [
                'user_id' => $user->id,
                'role_id' => Role::STUDENT_ROLE_ID,
            ];
            $this->userRoleService->create($role_data);

            // return msg
            return response()->json('New Student created!');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json('Something went wrong, couldn\'t create student!');
        }
    }

    public function update(Request $request, int $id)
    {
        $data = $request->all();
        try {

            $student = $this->studentService->getById($id);
            if (!$student) return response()->json('Can\'t find the target student!', 404);

            $user = $this->userService->getById($student->user->id);
            if (!$user) return response()->json("Couldn't find the target user!");

            // update user infor
            $update = $this->userService->update($user, $data);

            // if new infor is not valid
            if ($update !== true) {
                return $update;
            }


            // update student infor
            $updateStudentData = [
                'office_class_id' => $data['office_class_id'],
                'faculty_id' => $data['faculty_id'],
            ];

            $this->studentService->update($student, $updateStudentData);

            return response()->json('Student updated!');

        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json('Something went wrong, couldn\'t update student!');
        }
    }

    public function destroy(int $id)
    {
        try {

            $student = $this->studentService->getById($id);
            if (!$student) return response()->json('Can\'t find the target student!', 404);

            $user = $this->userService->getById($student->user->id);

            // delete from students table
            $this->studentService->delete($student);

            // delete from users_roles table
            $this->userRoleService->delete($user->userRole);

            // delete from users table
            $this->userService->delete($user);

            return response()->json("Student deleted!");
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json('Something went wrong, couldn\'t delete student!');
        }

    }
}
