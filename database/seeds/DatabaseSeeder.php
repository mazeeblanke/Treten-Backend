<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            BlogPostSeeder::class,
            TransactionSeeder::class,
            MessageSeeder::class,
            InstructorSeeder::class,
            CoursePathSeeder::class,
            CourseCategorySeeder::class,
            CourseSeeder::class,
            SettingSeeder::class,
            CourseBatchAuthorSeeder::class,
            ResourceSeeder::class,
            UserGroupSeeder::class,
            UserGroupAllocationSeeder::class,
            TestimonialSeeder::class
        ]);
        // $this->call(CourseCategorySeeder::class);
        // $this->call(CourseSeeder::class);
    }
}