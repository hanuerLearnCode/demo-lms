<?php

namespace App\Http\Controllers;

use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use App\Service\FacultyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FacultyController extends Controller
{
    protected FacultyService $facultyService;

    public function __construct(FacultyService $facultyService)
    {
        $this->facultyService = $facultyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $faculties = FacultyResource::collection($this->facultyService->getAll()->paginate(20));
        return view('faculties.index')->with([
            'faculties' => $faculties,
        ]);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(int $id)
    {
        $faculty = $this->facultyService->getById($id);

        if (!$faculty) return response()->json('Couldn\'t find the target faculty!');
        return view('faculties.profile')->with([
            'faculty' => new FacultyResource($faculty),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
        return view('faculties.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'abbreviation' => [
                    'required',
                    'string',
                    Rule::unique('faculties')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    }),
                ],
            ]);

            $this->facultyService->create($data);

            return redirect('faculties')->with([
                'success' => 'New faculty created!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new faculty, please try again!',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        //
        $faculty = $this->facultyService->getById($id);

        return view('faculties.edit')->with([
            'faculty' => $faculty,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $faculty = $this->facultyService->getById($id);

            if (!$faculty) return response()->json('Couldn\'t find the target faculty!');

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'abbreviation' => [
                    'required',
                    'string',
                    Rule::unique('faculties')->ignore($faculty->id)->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    }),
                ],
            ]);

            $update = $this->facultyService->update($faculty, $data);

            if (!$update) return redirect()->back()->with([
                'error' => 'Couldn\'t update this faculty, please try again!',
            ]);

            return redirect('/faculties')->with([
                'success' => 'Facutlty updated!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t update this faculty, please try again!',
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
        $faculty = $this->facultyService->getById($id);

        if (!$faculty) return redirect()->back()->with([
            'error' => 'Couldn\'t find this faculty!',
        ]);

        $deleted = $this->facultyService->delete($faculty);

        if (!$deleted) return redirect()->back()->with([
            'error' => 'Couldn\'t delete this faculty!'
        ]);

        return redirect('/faculties')->with([
            'success' => 'Facutlty deleted!',
        ]);
    }

    public function search(Request $request): string
    {
        $query = $request->input('query');

        // using laravel caching system to optimize search
        $faculties = Cache::remember('faculties_search_' . $query, 3600, function () use ($query) {
            return Faculty::where('name', 'like', "%$query%")
                ->orWhere('abbreviation', 'like', "%$query%")
                ->paginate(20); // optimize search with paginate
        });

        $count = 0;
        $html = '';

        if (count($faculties) <= 0) return '<tr><td colspan="5" class="px-6 py-4 text-center">No results for your search</td></tr>';

        foreach ($faculties as $faculty) {
            $count++;
            $html .= view('partials.faculty_row', compact('faculty', 'count'))->render();
        }
        return $html;
    }

    public function getClasses(int $id)
    {
        $faculty = $this->facultyService->getById($id);
        $classes = $faculty->officeClasses;
        return response()->json($classes);
    }
}
