<?php

namespace App\Observers;

use App\UserGroup;
use App\CourseBatchAuthor;

class CourseBatchAuthorObserver
{
    /**
     * Handle the course batch author "created" event.
     *
     * @param  \App\CourseBatchAuthor  $courseBatchAuthor
     * @return void
     */
    public function created(CourseBatchAuthor $courseBatchAuthor)
    {
        // $courseBatchAuthor = CourseBatchAuthor::whereCourseBatchId($courseBatch->id)->first();
        $course = $courseBatchAuthor->course;
        $courseBatch = $courseBatchAuthor->batch;
        $assignmentCount = CourseBatchAuthor::whereCourseId($course->id)->whereCourseBatchId($courseBatch->id)->count();
        if ($assignmentCount === 1)
        {
            $courseBatchGroup = UserGroup::firstOrCreate(
                [
                    'group_name' => "{$courseBatchAuthor->course->title} ({$courseBatch->batch_name}) group",
                    'group_description' => "This group is for all students enrolled to {$courseBatchAuthor->course->title}, {$courseBatch->batch_name} batch",
                ], 
                [
                    'group_name' => "{$courseBatchAuthor->course->title} ({$courseBatch->batch_name}) group",
                    'group_description' => "This group is for all students enrolled to {$courseBatchAuthor->course->title}, {$courseBatch->batch_name} batch",
                ]
            );
        }
    }

    /**
     * Handle the course batch author "updated" event.
     *
     * @param  \App\CourseBatchAuthor  $courseBatchAuthor
     * @return void
     */
    public function updated(CourseBatchAuthor $courseBatchAuthor)
    {
        //
    }

    /**
     * Handle the course batch author "deleted" event.
     *
     * @param  \App\CourseBatchAuthor  $courseBatchAuthor
     * @return void
     */
    public function deleted(CourseBatchAuthor $courseBatchAuthor)
    {
        //
    }

    /**
     * Handle the course batch author "restored" event.
     *
     * @param  \App\CourseBatchAuthor  $courseBatchAuthor
     * @return void
     */
    public function restored(CourseBatchAuthor $courseBatchAuthor)
    {
        //
    }

    /**
     * Handle the course batch author "force deleted" event.
     *
     * @param  \App\CourseBatchAuthor  $courseBatchAuthor
     * @return void
     */
    public function forceDeleted(CourseBatchAuthor $courseBatchAuthor)
    {
        //
    }
}
