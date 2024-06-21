<?php

namespace App\Service;

use App\Contract\ModelInterface;
use App\Contract\RepositoryInterface;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;

class FacultyService implements RepositoryInterface
{

    public function getAll()
    {
        return Faculty::with('officeClasses')->with('students');
    }

    public function getById(int $id)
    {
        return Faculty::with('officeClasses')->with('students')->find($id);
    }

    public function create(array $data = [])
    {
        return Faculty::create($data);
    }

    public function update(ModelInterface $model, array $data = [])
    {
        return $model->update($data);
    }

    public function delete(ModelInterface $model)
    {
        return $model->delete();
    }

}
