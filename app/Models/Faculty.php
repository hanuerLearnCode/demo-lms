<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'faculties';

    public function officeClasses()
    {
        return $this->hasMany(OfficeClass::class, 'faculty_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'faculty_id');
    }
}
