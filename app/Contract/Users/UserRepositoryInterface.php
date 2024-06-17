<?php

namespace App\Contract\Users;


interface UserRepositoryInterface
{

    public function getAll();

    public function getById(int $id);

    public function create(array $data = []);

    public function update(UserInterface $user, array $data = []);

    public function delete(UserInterface $user);
}
