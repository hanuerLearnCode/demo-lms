<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\OfficeClass;
use App\Service\CourseService;
use App\Service\FacultyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $course = $this->courseService->getById($id);

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'abbreviation' => [
                    'required',
                    'string',
                    Rule::unique('courses')->ignore($course->id)->where(function ($query) use ($request) {
                        return $query->where('faculty_id', $request->faculty_id);
                    })
                ],
                'enrollment_key' => 'required|string|max:255',
                'credit' => 'required|string|min:1',
                'faculty_id' => 'required|exists:faculties,id',
            ]);

            $this->courseService->update($course, $data);

            return redirect('/courses')->with([
                'success' => 'Course updated!',
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t update this course, please try again!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(int $id)
    {
        //
        $course = $this->courseService->getById($id);

        try {
            $this->courseService->delete($course);

            return redirect('/courses')->with([
                'success' => 'Course deleted!',
            ]);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t delete this course!',
            ]);
        }
    }

    public function search(Request $request)
    {

        $query = $request->input('query');

        // using laravel caching system to optimize search
        $courses = Cache::remember('courses_search' . $query, 3600, function () use ($query) {
            return Course::join('faculties', 'courses.faculty_id', '=', 'faculties.id')
                ->where(function ($q) use ($query) {
                    $q->where('courses.name', 'like', "%$query%")
                        ->orWhere('courses.abbreviation', 'like', "%$query%")
                        ->orWhere('faculties.name', 'like', "%$query%")
                        ->orWhere('faculties.abbreviation', 'like', "%$query%");
                })
                ->select('courses.*', 'faculties.name as faculty_name')
                ->paginate(10); // optimize search with paginate
        });

        $count = 0;
        $html = '';

        if (count($courses) <= 0) return '<tr><td colspan="7" class="px-6 py-4 text-center">No results for your search</td></tr>';

        foreach ($courses as $course) {
            $count++;
            $html .= view('partials.course_row', compact('course', 'count'))->render();
        }
        return $html;

    }
}
