<?php

namespace App\Contract;

use Illuminate\Http\Request;

interface RepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function create(array $data = []);

    public function update(ModelInterface $model, array $data = []);

    public function delete(ModelInterface $model);
}
