<?php

namespace App\Service;

use App\Contract\ModelInterface;
use App\Contract\RepositoryInterface;
use App\Models\Course;

class CourseService implements RepositoryInterface
{
    public function getAll()
    {
        return Course::with('faculty')->with('students')->with('courseStudent')->get();
    }

    public function getById(int $id)
    {
        return Course::with('faculty')->with('students')->with('courseStudent')->find($id);
    }

    public function create(array $data = [])
    {
    }

    public function update(ModelInterface $model, array $data = [])
    {
    }

    public function delete(ModelInterface $model)
    {
    }
}