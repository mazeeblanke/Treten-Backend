<?php

use App\User;
use App\UserGroup;
use App\UserGroupAllocation;
use Illuminate\Database\Seeder;

class UserGroupAllocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = User::whereHas('roles', function ($query) {
            return $query->where('roles.name', 'admin');
        })->get();
        $adminGroup = UserGroup::whereGroupName('all admins')->first();
        $admins->each(function ($admin) use ($adminGroup) {
            factory(UserGroupAllocation::class)->create([
                'user_id' => $admin->id,
                'user_group_id' => $adminGroup->id
            ]);
        });

        $students = User::whereHas('roles', function ($query) {
            return $query->where('roles.name', 'student');
        })->get();
        $studentGroup = UserGroup::whereGroupName('all students')->first();
        $students->each(function ($student) use ($studentGroup) {
            factory(UserGroupAllocation::class)->create([
                'user_id' => $student->id,
                'user_group_id' => $studentGroup->id
            ]);
        });

        $instructors = User::whereHas('roles', function ($query) {
            return $query->where('roles.name', 'instructor');
        })->get();
        $instructorGroup = UserGroup::whereGroupName('all instructors')->first();
        $instructors->each(function ($instructor) use ($instructorGroup) {
            factory(UserGroupAllocation::class)->create([
                'user_id' => $instructor->id,
                'user_group_id' => $instructorGroup->id
            ]);
        });
    }
}
