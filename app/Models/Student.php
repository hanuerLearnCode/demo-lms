<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Student extends User
{
    use HasFactory;

    /**
     * ADD THIS LINE TO SAVE STUDENT DATA to users table
     * @var string
     */
    protected $table = 'students';

    protected $fillable = [
        'office_class',
    ];

    protected string $office_class;

    public function getOfficeClass(): string
    {
        return $this->office_class;
    }

    /**
     * @param string $office_class
     */
    public function setOfficeClass(string $office_class): void
    {
        $this->office_class = $office_class;
    }
}
