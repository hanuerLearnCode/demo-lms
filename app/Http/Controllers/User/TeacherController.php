<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\TeacherResource;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Service\FacultyService;
use App\Service\User\TeacherService;
use App\Service\User\UserRoleService;
use App\Service\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    //

    protected UserService $userService;
    protected TeacherService $teacherService;
    protected UserRoleService $userRoleService;
    protected FacultyService $facultyService;


    public function __construct(UserService     $userService, TeacherService $teacherService,
                                UserRoleService $userRoleService, FacultyService $facultyService)
    {
        $this->userService = $userService;
        $this->teacherService = $teacherService;
        $this->userRoleService = $userRoleService;
        $this->facultyService = $facultyService;
    }

    public function index()
    {
        $teachers = TeacherResource::collection($this->teacherService->getAll()->orderBy('faculty_id')->paginate(20));
        return view('teachers.index', compact('teachers'));
    }

    public function show(int $id)
    {
        if ($this->teacherService->getById($id))
            return new TeacherResource($this->teacherService->getById($id));

        return response()->json("Can not find the target teacher!");
    }

    public function create()
    {
        $faculties = $this->facultyService->getAll()->get();
        return view('teachers.add')->with([
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
            $teacher_infor = [
                'user_id' => $user->id,
                'faculty_id' => $data['faculty_id'],
                'position' => $data['position'],
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
            return redirect('/teachers')->with([
                'success' => 'New Teacher created!',
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
        $teacher = $this->teacherService->getById($id);
        $faculties = $this->facultyService->getAll()->get();
        return view('teachers.edit')->with([
            'teacher' => $teacher,
            'faculties' => $faculties,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->all();
        try {
            $teacher = $this->teacherService->getById($id);
            if (!$teacher) return redirect()->back()->withErrors($teacher)->withInput();
            $user = $this->userService->getById($teacher->user->id);
            if (!$user) return redirect()->back()->withErrors($user)->withInput();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                $data['password'] = $user->password;
            }
            // update user infor
            $update = $this->userService->update($user, $data);

            // if new infor is not valid
            // if new infor is not valid
            if ($update !== true) {
                return redirect()->back()->withErrors($update)->withInput();
            }

            if (!isset($data['salary'])) $data['salary'] = $teacher->salary;
            if (!isset($data['experience'])) $data['experience'] = $teacher->experience;
            if (!isset($data['position'])) $data['position'] = $teacher->position;
            if (!isset($data['faculty_id'])) $data['faculty_id'] = $teacher->faculty->id;

            $teacher_infor = [
                'faculty_id' => $data['faculty_id'],
                'position' => $data['position'],
                'salary' => $data['salary'],
                'experience' => $data['experience'],
            ];

            // update teacher infor
            $this->teacherService->update($teacher, $teacher_infor);


            return redirect('/teachers')->with([
                'success' => 'Teacher updated!',
            ]);

        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new teacher!',
            ]);
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

    public function search(Request $request)
    {
        $query = $request->input('query');

        // using laravel caching system to optimize search
        $teachers = Cache::remember('teachers_search' . $query, 3600, function () use ($query) {
            return Teacher::join('users', 'teachers.user_id', '=', 'users.id')
                ->join('faculties', 'teachers.faculty_id', '=', 'faculties.id')
                ->where(function ($q) use ($query) {
                    $q->where('users.name', 'like', "%$query%")
                        ->orWhere('faculties.name', 'like', "%$query%")
                        ->orWhere('faculties.abbreviation', 'like', "%$query%");
                })
                ->select('teachers.*')
                ->paginate(10); // optimize search with paginate
        });

        $count = 0;
        $html = '';

        if (count($teachers) <= 0) return '<tr><td colspan="7" class="px-6 py-4 text-center">No results for your search</td></tr>';

        foreach ($teachers as $teacher) {
            $count++;
            $html .= view('partials.teacher_row', compact('teacher', 'count'))->render();
        }
        return $html;
    }
}
