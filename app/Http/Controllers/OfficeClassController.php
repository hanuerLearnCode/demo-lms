<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfficeClassResource;
use App\Models\Faculty;
use App\Models\OfficeClass;
use App\Service\FacultyService;
use App\Service\OfficeClassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OfficeClassController extends Controller
{

    protected OfficeClassService $officeClassService;
    protected FacultyService $facultyService;

    public function __construct(OfficeClassService $officeClassService, FacultyService $facultyService)
    {
        $this->officeClassService = $officeClassService;
        $this->facultyService = $facultyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $classes = OfficeClassResource::collection($this->officeClassService->getAll()
            ->orderBy('faculty_id', 'asc')->paginate(10));
        return view('classes.index')->with([
            'classes' => $classes,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(int $id)
    {
        //
        return new OfficeClassResource($this->officeClassService->getById($id));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $faculties = $this->facultyService->getAll()->get();
        return view('classes.add')->with([
            'faculties' => $faculties,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $data = $request->all();

        unset($data['_token']);

        try {

            $data = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('office_classes')->where(function ($query) use ($request) {
                        return $query->where('faculty_id', $request->faculty_id)
                            ->whereNull('deleted_at');
                    })
                ],
                'faculty_id' => 'required|exists:faculties,id',
            ]);

            $this->officeClassService->create($data);

            return redirect('/office_classes')->with([
                'success' => 'New class created!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t create new class, please try again!',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit(int $id)
    {
        //
        $class = $this->officeClassService->getById($id);
        $faculties = $this->facultyService->getAll()->get();
        return view('classes.edit')->with([
            'class' => $class,
            'faculties' => $faculties,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //

        try {
            $class = $this->officeClassService->getById($id);

            $data = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('office_classes')->ignore($class->id)->where(function ($query) use ($request) {
                        return $query->where('faculty_id', $request->faculty_id)->whereNull('deleted_at');
                    })
                ],
                'faculty_id' => 'required|exists:faculties,id',
            ]);

            $this->officeClassService->update($class, $data);

            return redirect('/office_classes')->with([
                'success' => 'Class updated!',
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
     * @param int $id
     */
    public function destroy(int $id)
    {
        //
        $class = $this->officeClassService->getById($id);

        try {
            $this->officeClassService->delete($class);

            return redirect('/office_classes')->with([
                'success' => 'Class deleted!',
            ]);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return redirect()->back()->with([
                'error' => 'Couldn\'t delete this class!',
            ]);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // using laravel caching system to optimize search
        $classes = Cache::remember('office_classes_search' . $query, 3600, function () use ($query) {
            return OfficeClass::join('faculties', 'office_classes.faculty_id', '=', 'faculties.id')
                ->where(function ($q) use ($query) {
                    $q->where('office_classes.name', 'like', "%$query%")
                        ->orWhere('faculties.name', 'like', "%$query%");
                })
                ->select('office_classes.*', 'faculties.name as faculty_name')
                ->paginate(10); // optimize search with paginate
        });

        $count = 0;
        $html = '';

        if (count($classes) <= 0) return '<tr><td colspan="5" class="px-6 py-4 text-center">No results for your search</td></tr>';

        foreach ($classes as $class) {
            $count++;
            $html .= view('partials.class_row', compact('class', 'count'))->render();
        }
        return $html;
    }
}
