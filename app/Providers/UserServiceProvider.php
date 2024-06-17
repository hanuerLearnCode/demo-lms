<?php

namespace App\Providers;

use App\Contract\Users\UserInterface;
use App\Models\Student;
use App\Models\User;
use App\Service\User\StudentService;
use App\Service\User\UserService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
