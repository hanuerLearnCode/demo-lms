<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfficeClassResource;
use App\Service\OfficeClassService;
use Illuminate\Http\Request;

class OfficeClassController extends Controller
{

    protected OfficeClassService $officeClassService;

    public function __construct(OfficeClassService $officeClassService)
    {
        $this->officeClassService = $officeClassService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return OfficeClassResource::collection($this->officeClassService->getAll());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
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
