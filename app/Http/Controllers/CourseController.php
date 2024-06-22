<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Service\CourseService;
use App\Service\FacultyService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    protected CourseService $courseService;
    protected FacultyService $facultyService;

    public function __construct(CourseService $courseService, FacultyService $facultyService)
    {
        $this->courseService = $courseService;
        $this->facultyService = $facultyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $courses = CourseResource::collection($this->courseService->getAll()->orderBy('faculty_id')->paginate(20));
        return view('courses.index')->with([
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $faculties = $this->facultyService->getAll()->get();
        return view('courses.add')->with([
            'faculties' => $faculties,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'abbreviation' => [
                    'required',
                    'string',
                    Rule::unique('courses')->where(function ($query) use ($request) {
                        return $query->where('faculty_id', $request->faculty_id);
                    })
                ],
                'enrollment_key' => 'required|string|max:255',
                'credit' => 'required|string|min:1',
                'faculty_id' => 'required|exists:faculties,id',
            ]);

            $this->courseService->create($data);

            return redirect('/courses')->with([
                'success' => 'New course created!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new course, please try again!',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
        $course = $this->courseService->getById($id);
        $faculties = $this->facultyService->getAll()->get();
        return view('courses.edit')->with([
            'course' => $course,
            'faculties' => $faculties,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
