<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Setting::class)->create([
            'setting_name' => 'popularCourses',
            'setting_value' => serialize('{"1": 2, "2": 10, "3": 4, "4": 7, "5": 12, "6": 9}'),
        ]);
    }
}