<?php

namespace App\Service;

use App\Contract\ModelInterface;
use App\Contract\RepositoryInterface;
use App\Models\OfficeClass;

class OfficeClassService implements RepositoryInterface
{
    public function getAll()
    {
        return OfficeClass::with('students')->with('faculty');
    }

    public function getById(int $id)
    {
        return OfficeClass::with('students')->with('faculty')->findOrFail($id);
    }

    public function create(array $data = [])
    {
        return OfficeClass::create($data);
    }

    public function update(ModelInterface $officeClass, array $data = [])
    {
        return $officeClass->update($data);
    }

    public function delete(ModelInterface $officeClass)
    {
        return $officeClass->delete();
    }
}
