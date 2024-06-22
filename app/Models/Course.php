<?php

namespace App\Models;

use App\Contract\ModelInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model implements ModelInterface
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'name',
        'abbreviation',
        'enrollment_key',
        'credit',
        'faculty_id',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'students_courses', 'course_id', 'student_id');
    }

    public function courseStudent()
    {
        return $this->hasMany(StudentCourse::class, 'course_id');
    }
}
