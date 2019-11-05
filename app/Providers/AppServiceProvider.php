<?php

namespace App\Providers;

use App\User;
use App\Student;
use App\Instructor;
use App\Observers\UserObserver;
use App\Observers\StudentObserver;
use App\Observers\InstructorObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Database\Query\Grammars\MySqlGrammar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        

        Schema::defaultStringLength(191);
        User::observe(UserObserver::class);
        Student::observe(StudentObserver::class);
        Instructor::observe(InstructorObserver::class);
    }
}
