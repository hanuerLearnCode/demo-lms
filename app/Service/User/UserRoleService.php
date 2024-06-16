<?php

namespace App\Service\User;

use App\Models\UserRole;

class UserRoleService
{
    public function getById(int $id)
    {
        return UserRole::find($id);
    }

    // create a new instance in the user_role table
    public function create(array $data = [])
    {
        return UserRole::create($data);
    }

    // update the role of a user
    public function update(UserRole $userRole, array $data = [])
    {
        return $userRole->update($data);
    }

    // delete a role when a user is deleted
    public function delete(UserRole $userRole)
    {
        return $userRole->delete();
    }
}
