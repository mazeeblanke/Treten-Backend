<?php

use App\CourseBatchAuthor;
use Illuminate\Database\Seeder;

class CourseBatchAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = Faker\Factory::create();
        factory(CourseBatchAuthor::class, 15)->create();
    }
}