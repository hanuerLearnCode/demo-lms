<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public const ADMIN_ROLE_ID = 1;
    public const STUDENT_ROLE_ID = 2;
    public const TEACHER_ROLE_ID = 3;
    public const PARENT_ROLE_ID = 4;

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_roles', 'role_id', 'user_id');
    }

    public function roleUser()
    {
        return $this->hasOne(UserRole::class, 'role_id');
    }

}
