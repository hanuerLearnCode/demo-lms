<?php

namespace App\Http\Controllers;

use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use App\Service\FacultyService;
use Illuminate\Http\Request;
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
        return FacultyResource::collection($this->facultyService->getAll());
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(int $id)
    {
        $faculty = $this->facultyService->getById($id);

        if (!$faculty) return response()->json('Couldn\'t find the target faculty!');
        return new FacultyResource($faculty);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
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

            return response()->json('New faculty created!');
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json('Couldn\'t create new faculty!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id)
    {
        //
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


            $this->facultyService->update($faculty, $data);

            return response()->json('Faculty updated successfully!');
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return response()->json('Couldn\'t update faculty!');
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

        if (!$faculty) return response()->json('Couldn\'t find the target faculty!');

        $this->facultyService->delete($faculty);
        return response()->json('Faculty deleted!');
    }
}
