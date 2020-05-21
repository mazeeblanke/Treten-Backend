<?php

namespace App\Observers;

use App\Course;
use App\UserGroup;

class CourseObserver
{
    // public function creating(Cases $case)
    // {
    //     dd('creating');
    // }
    /**
     * Handle the course "created" event.
     *
     * @param  \App\Course  $course
     * @return void
     */
    public function created(Course $course)
    {
        $courseGroup = UserGroup::firstOrCreate(
            [
                'group_name' => "{$course->title} group",
                'group_description' => "This group is for all students enrolled to {$course->title}",
            ], 
            [
                'group_name' => "{$course->title} group",
                'group_description' => "This group is for all students enrolled to {$course->title}",
            ]
        );
        // dd('dffhj');
    }

    /**
     * Handle the course "updated" event.
     *
     * @param  \App\Course  $course
     * @return void
     */
    public function updated(Course $course)
    {
        //
    }

    /**
     * Handle the course "deleted" event.
     *
     * @param  \App\Course  $course
     * @return void
     */
    public function deleted(Course $course)
    {
        //
    }

    /**
     * Handle the course "restored" event.
     *
     * @param  \App\Course  $course
     * @return void
     */
    public function restored(Course $course)
    {
        //
    }

    /**
     * Handle the course "force deleted" event.
     *
     * @param  \App\Course  $course
     * @return void
     */
    public function forceDeleted(Course $course)
    {
        //
    }
}
