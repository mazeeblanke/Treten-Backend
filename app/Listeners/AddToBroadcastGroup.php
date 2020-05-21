<?php

namespace App\Listeners;

use App\UserGroup;
use App\Events\NewUserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddToBroadcastGroup
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewUserRegistered  $event
     * @return void
     */
    public function handle(NewUserRegistered $event)
    {
        $role = optional($event->user->roles->first())->name;
        if (!$role) return;
        if ($role === 'admin') {
            // $group = UserGroup::whereGroupName('all admins')->first();
            $group = UserGroup::firstOrCreate(
                ['group_name' => 'all admins'],
                ['group_name' => 'all admins']
            );
        }

        if ($role === 'instructor') {
            // $group = UserGroup::whereGroupName('all instructors')->first();
            $group = UserGroup::firstOrCreate(
                ['group_name' => 'all instructors'],
                ['group_name' => 'all instructors']
            );
        }

        if ($role === 'student') {
            // $group = UserGroup::whereGroupName('all students')->first();
            $group = UserGroup::firstOrCreate(
                ['group_name' => 'all students'],
                ['group_name' => 'all students']
            );
        }

        $event->user->userGroups()->attach($group->id);
    }
}
