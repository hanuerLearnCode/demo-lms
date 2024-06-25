<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\StudentResource;
use App\Models\OfficeClass;
use App\Models\Role;
use App\Models\User;
use App\Service\FacultyService;
use App\Service\OfficeClassService;
use App\Service\User\StudentService;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    //
    protected UserService $userService;
    protected StudentService $studentService;
    protected UserRoleService $userRoleService;
//    protected OfficeClassService $officeClassService;
    protected FacultyService $facultyService;

    public function __construct(UserService     $userService, StudentService $studentService,
                                UserRoleService $userRoleService, FacultyService $facultyService)
    {
        $this->userService = $userService;
        $this->studentService = $studentService;
        $this->userRoleService = $userRoleService;
//        $this->officeClassService = $officeClassService;
        $this->facultyService = $facultyService;
    }

    public function index()
    {
        $students = StudentResource::collection($this->studentService->getAll()->orderBy('faculty_id')->paginate(10));
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
        $faculties = $this->facultyService->getAll()->get();
        return view('students.add')->with([
            'faculties' => $faculties,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        unset($data['_token']);

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
            return redirect('/students')->with([
                'success' => 'New Student created!',
            ]);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new student!',
            ]);
        }
    }

    public function edit(int $id)
    {
        $student = $this->studentService->getById($id);
        $faculties = $this->facultyService->getAll()->get();
        return view('students.edit')->with([
            'student' => $student,
            'faculties' => $faculties,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->all();
        unset($data['_token']);


        try {

            $student = $this->studentService->getById($id);
            if (!$student) return redirect()->back()->withErrors($student)->withInput();
            $user = $this->userService->getById($student->user->id);
            if (!$user) return redirect()->back()->withErrors($user)->withInput();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                $data['password'] = $user->password;
            }
            // update user infor
            $update = $this->userService->update($user, $data);

            // if new infor is not valid
            if ($update !== true) {
                return redirect()->back()->withErrors($update)->withInput();
            }

            if (!isset($data['office_class_id'])) $data['office_class_id'] = $student->officeClass->id;
            if (!isset($data['faculty_id'])) $data['faculty_id'] = $student->faculty->id;

            // update student infor
            $updateStudentData = [
                'office_class_id' => $data['office_class_id'],
                'faculty_id' => $data['faculty_id'],
            ];

//            $updateStudentData = $request->validate($updateStudentData, [
//                'faculty_id' => '',
//                'office_class_id' => ''
//            ]);

            $this->studentService->update($student, $updateStudentData);

            return redirect('/students')->with([
                'success' => 'Student updated!',
            ]);

        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new student!',
            ]);
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

            return redirect('/students')->with([
                'success' => 'Student deleted!',
            ]);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t delete this student!',
            ]);
        }

    }
}
