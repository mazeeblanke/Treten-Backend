<?php

namespace App\Observers;

use App\User;
use App\UserGroup;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        if (request('password')) {
            $user->password = Hash::make(request('password'));
        }
    }


    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // $role = optional($user->roles->first())->name;
        // if (!$role) return;
        // if ($role === 'admin') {
        //     $group = UserGroup::whereGroupName('all admins')->first();
        // }

        // // if ($role === 'instructor') {
        // //     $group = UserGroup::whereGroupName('all instructors')->first();
        // // }

        // // if ($role === 'student') {
        // //     $group = UserGroup::whereGroupName('all students')->first();
        // // }

        // $user->userGroups()->attach($group->id);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // dd('sdsd');
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
