<?php

namespace App\Observers;

use App\Instructor;

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
        $data = array_merge(
            request()->all(),
            [
                'status' => 'pending'
            ]);
        $instructor->details()->create($data);
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
