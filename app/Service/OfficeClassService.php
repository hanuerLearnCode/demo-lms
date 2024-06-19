<?php

namespace App\Service;

use App\Contract\RepositoryInterface;
use App\Models\OfficeClass;

class OfficeClassService implements RepositoryInterface
{
    public function getAll()
    {
        return OfficeClass::with('students')->with('faculty')->get();
    }

    public function getById(int $id)
    {
        return OfficeClass::with('students')->with('faculty')->findOrFail($id);
    }

    public function create(array $data = [])
    {

    }

    public function update(OfficeClass $officeClass, array $data = [])
    {
    }

    public function delete(OfficeClass $officeClass)
    {
    }
}
