<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function officeClass()
    {
        return $this->belongsTo(OfficeClass::class, 'office_class_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
