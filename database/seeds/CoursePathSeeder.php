<?php

use App\CoursePath;
use Illuminate\Database\Seeder;

class CoursePathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CoursePath::class)->create([
            'name' => 'Cisco Service Provider',
            'description' => ''
        ]);

        factory(CoursePath::class)->create([
            'name' => 'Cisco Security',
            'description' => ''
        ]);

        factory(CoursePath::class)->create([
            'name' => 'Cisco R&S',
            'description' => ''
        ]);

        factory(CoursePath::class)->create([
            'name' => 'Cisco Collaboration',
            'description' => ''
        ]);

        factory(CoursePath::class)->create([
            'name' => 'Cisco Datacenter',
            'description' => ''
        ]);

        factory(CoursePath::class)->create([
            'name' => 'Firewall Expert',
            'description' => ''
        ]);
    }
}
