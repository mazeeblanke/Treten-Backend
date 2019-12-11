<?php

use App\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CourseCategory::class)->create([
            'name' => 'associate'
        ]);
        
        factory(CourseCategory::class)->create([
            'name' => 'professional'
        ]);
        
        factory(CourseCategory::class)->create([
            'name' => 'expert'
        ]);
    }
}