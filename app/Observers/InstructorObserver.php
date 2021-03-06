<?php

namespace App\Observers;

use App\Instructor;
use App\Events\NewUserRegistered;
use Spatie\Permission\Models\Role;

class InstructorObserver
{
    /**
     * Handle the instructor "created" event.
     *
     * @param  \App\Instructor  $instructor
     * @return void
     */
    public function created(Instructor $instructor)
    {
        if (count(request()->all()))
        {
            $role = Role::firstOrCreate(['name' => 'instructor'], ['name' => 'instructor']);
            $data = array_merge(
                request()->all(),
                [
                    // 'status' => 'active'
                ]);
            $instructor->details()->create($data);
            $instructor->details->assignRole($role);
            
            event(new NewUserRegistered($instructor->details));
        }
    }

    /**
     * Handle the instructor "updated" event.
     *
     * @param  \App\Instructor  $instructor
     * @return void
     */
    public function updated(Instructor $instructor)
    {
        //
    }

    /**
     * Handle the instructor "deleted" event.
     *
     * @param  \App\Instructor  $instructor
     * @return void
     */
    public function deleted(Instructor $instructor)
    {
        //
    }

    /**
     * Handle the instructor "restored" event.
     *
     * @param  \App\Instructor  $instructor
     * @return void
     */
    public function restored(Instructor $instructor)
    {
        //
    }

    /**
     * Handle the instructor "force deleted" event.
     *
     * @param  \App\Instructor  $instructor
     * @return void
     */
    public function forceDeleted(Instructor $instructor)
    {
        //
    }
}
