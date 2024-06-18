<?php

namespace App\Models;

use App\Contract\Users\UserInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model implements UserInterface
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'faculty_id',
        'salary',
        'experience',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
