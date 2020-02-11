<?php

namespace Tests\Feature;

use App\Instructor;
use App\Student;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp (): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

    }

    public function testDownloadCsv()
    {
        // Instructor::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $ola = factory(Instructor::class)->create();
        $ola->details->status = 'active';
        $ola->details->first_name = 'ola';
        $ola->details->save();

        $response = $this->json(
            'GET',
            'api/instructors/download'
        );

        $response->assertJson([
            ['Name', 'Email Address', 'Phone Number', 'Status', 'Sign up date and time'],
            ["{$john->details->first_name} {$john->details->last_name}", "{$john->details->email}", "{$john->details->phone_number}", "{$john->details->status}", "{$john->details->created_at->format('Y-m-d')}"],
            ["{$ola->details->first_name} {$ola->details->last_name}", "{$ola->details->email}", "{$ola->details->phone_number}", "{$ola->details->status}", "{$ola->details->created_at->format('Y-m-d')}"],
        ]);

        $response->assertStatus(200);
    }

    public function testFetchAllUsersFiltering ()
    {
        // Instructor::unsetEventDispatcher();
        // Student::unsetEventDispatcher();

        $molade = factory(Student::class)->create();
        $molade->details->first_name = 'molamide';
        $molade->details->save();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $ola = factory(Instructor::class)->create();
        $ola->details->status = 'active';
        $ola->details->first_name = 'olamide';
        $ola->details->save();

        $page = 1;

        $response = $this->json(
            'GET',
            'api/users?q=olamide'
        );

        // dd($response->getContent());

        // $response->assertJsonFragment([
        //     'current_page' => $page,
        // ]);

        $response->assertSee($molade->details->first_name);
        $response->assertDontSee($john->details->first_name);
        $response->assertSee($ola->details->first_name);
        // $response->assertSee('Successfully fetched users');

    }


    public function testUpdateUserDetails ()
    {

        // Student::unsetEventDispatcher();

        $molade = factory(Student::class)->create();
        $molade->details->first_name = 'molamide';
        $molade->details->save();

        $password = '12345678';
        $response = $this
        ->actingAs($molade->details)
        ->json(
            'POST',
            'api/user',
            [
                'firstName' => 'molape',
                'lastName' => 'dolade',
                'password' => $password
            ]
        );
        //  dd( $response->getContent());

        $this->assertDatabaseHas('users', [
            'first_name' => 'molape',
            'last_name' => 'dolade',
        ]);

        $this->assertTrue(\Hash::check($password, User::find($molade->details->id)->password ));

        $response->assertStatus(200);

    }


    public function testCannotUpdateUserDetailsWithoutAuth ()
    {
        // Student::unsetEventDispatcher();

        $molade = factory(Student::class)->create();
        $molade->details->first_name = 'molamide';
        $molade->details->save();

        $response = $this
        ->json(
            'POST',
            'api/user',
            [
                'first_name' => 'molape',
                'last_name' => 'dolade',
                'password' => '12345678'
            ]
        );

        // dd( $response->getContent());
        $response->assertSee("Unauthenticated.");
        $response->assertStatus(401);

    }
}
