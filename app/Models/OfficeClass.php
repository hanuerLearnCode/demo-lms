<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeClass extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'office_classes';

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'office_class_id');
    }
}
