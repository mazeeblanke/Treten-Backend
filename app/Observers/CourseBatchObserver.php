<?php

namespace App\Observers;

use App\UserGroup;
use App\CourseBatch;
use App\CourseBatchAuthor;

class CourseBatchObserver
{
    /**
     * Handle the course batch "created" event.
     *
     * @param  \App\CourseBatch  $courseBatch
     * @return void
     */
    public function created(CourseBatch $courseBatch)
    {
        // $courseBatchAuthor = CourseBatchAuthor::whereCourseBatchId($courseBatch->id)->first();
        // $courseBatchGroup = UserGroup::firstOrCreate(
        //     [
        //         'group_name' => "{$courseBatchAuthor->course->title} ({$courseBatch->batch_name}) group",
        //         'group_description' => "This group is for all students enrolled to {$courseBatchAuthor->course->title}, {$courseBatch->batch_name} batch",
        //     ], 
        //     [
        //         'group_name' => "{$courseBatchAuthor->course->title} ({$courseBatch->batch_name}) group",
        //         'group_description' => "This group is for all students enrolled to {$courseBatchAuthor->course->title}, {$courseBatch->batch_name} batch",
        //     ]
        // );
    }

    /**
     * Handle the course batch "updated" event.
     *
     * @param  \App\CourseBatch  $courseBatch
     * @return void
     */
    public function updated(CourseBatch $courseBatch)
    {
        //
    }

    /**
     * Handle the course batch "deleted" event.
     *
     * @param  \App\CourseBatch  $courseBatch
     * @return void
     */
    public function deleted(CourseBatch $courseBatch)
    {
        //
    }

    /**
     * Handle the course batch "restored" event.
     *
     * @param  \App\CourseBatch  $courseBatch
     * @return void
     */
    public function restored(CourseBatch $courseBatch)
    {
        //
    }

    /**
     * Handle the course batch "force deleted" event.
     *
     * @param  \App\CourseBatch  $courseBatch
     * @return void
     */
    public function forceDeleted(CourseBatch $courseBatch)
    {
        //
    }
}
