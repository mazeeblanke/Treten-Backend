<?php

use App\UserGroup;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allStudents = factory(UserGroup::class)->create([
            'group_name' => 'all students',
            'group_description' => 'All students in the system'
        ]);

        $allAdmins = factory(UserGroup::class)->create([
            'group_name' => 'all admins',
            'group_description' => 'All admins in the system'
        ]);

        $allInstructors = factory(UserGroup::class)->create([
            'group_name' => 'all instructors',
            'group_description' => 'All instructors in the system'
        ]);
    }
}
