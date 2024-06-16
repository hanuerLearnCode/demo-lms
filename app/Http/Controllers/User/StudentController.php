<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\StudentResource;
use App\Service\User\StudentService;

class StudentController extends Controller
{
    //
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function getAll()
    {
        return StudentResource::collection($this->studentService->getAll());
    }

    public function getById(int $id)
    {
        return $this->studentService->getById($id);
    }
}
