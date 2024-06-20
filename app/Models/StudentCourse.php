<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentCourse extends Pivot
{
    use HasFactory;

    public const FEE_OF_CREDIT = 650;

    protected $table = 'students_courses';

    protected $fillable = [
        'student_id',
        'course_id',
        'fee',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
