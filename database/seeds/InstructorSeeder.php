<?php

use App\Instructor;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Instructor::unsetEventDispatcher();

        $annie = factory(Instructor::class)->create([
            'qualifications' => 'CCIE Datacenter #51815 | JNCIP | CCSM | VCP',
            'title' => 'Co-founder'
        ]);
        $annie->details->first_name = 'Aniebiet-Abasi';
        $annie->details->last_name = 'Udo';
        $annie->details->profile_pic = '/instructors/anie.png';
        $annie->details->status = 'active';
        $annie->details->phone_number = '+23408039093635';
        $annie->details->save();

        $daniel = factory(Instructor::class)->create([
            'qualifications' => 'CCIE Security #53707 | OSCP | CEH | CCNP (RS) |PCNSE7',
            'title' => 'Co-founder'
        ]);
        $daniel->details->first_name = 'Daniel';
        $daniel->details->last_name = 'Adebayo';
        $daniel->details->profile_pic = '/instructors/daniel.png';
        $daniel->details->status = 'active';
        $daniel->details->phone_number = '+23408039093635';
        $daniel->details->save();

        $coker = factory(Instructor::class)->create([
            'qualifications' => 'CCIE #56262 | Data Center | Nexus | ACI | Cloud',
            'title' => 'Network Engineer'
        ]);
        $coker->details->first_name = 'Oswald';
        $coker->details->last_name = 'Coker';
        $coker->details->profile_pic = '/instructors/coker.png';
        $coker->details->status = 'active';
        $coker->details->phone_number = '+23408039093635';
        $coker->details->save();

        $odunaye = factory(Instructor::class)->create([
            'qualifications' => '2xCCIE #50238(SEC,R&S)....',
            'title' => 'Network Engineer'
        ]);
        $odunaye->details->first_name = 'Abdulrahman';
        $odunaye->details->last_name = 'Odunaye';
        $odunaye->details->other_name = '.O.';
        $odunaye->details->profile_pic = '/instructors/odunaye.png';
        $odunaye->details->status = 'active';
        $odunaye->details->phone_number = '+23408039093635';
        $odunaye->details->save();

        $harpreet = factory(Instructor::class)->create([
            'qualifications' => 'CCIE R&S #51369',
            'title' => 'Senior Network Engineer'
        ]);
        $harpreet->details->first_name = 'Harpreet';
        $harpreet->details->last_name = 'Singh';
        $harpreet->details->profile_pic = '/instructors/harpreet.png';
        $harpreet->details->status = 'active';
        $harpreet->details->phone_number = '+23408039093635';
        $harpreet->details->save();

    }
}
