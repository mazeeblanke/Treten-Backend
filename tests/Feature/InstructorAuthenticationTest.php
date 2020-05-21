<?php

namespace Tests\Feature;

use Faker\Factory;
use App\Instructor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InstructorAuthenticationTest extends TestCase
{

    use RefreshDatabase;
    // use DatabaseTransactions;

    public function testAnInstructorCanSignUp()
    {
        $password = '12345678';
        $faker = Factory::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $otherName = $faker->lastName;
        $qualifications = $faker->sentence;
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'first_name' => $firstName,
                'last_name' =>   $lastName,
                'qualifications' => $qualifications,
                'consideration' =>  $faker->paragraph,
                // 'profile_pic' => '',
                'other_name' => $otherName,
                'email' => $faker->email,
                'as' => 'instructor',
                'phone_number' => $faker->phoneNumber,
                'password' => $password
            ]
        );

        $instructor = Instructor::whereQualifications($qualifications)->first();

        $this->assertEquals($instructor->qualifications, $qualifications);

        $response->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAnInstructorCanLogin()
    {
        // Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

        $instructor->details->status = 'active';
        $instructor->details->save();

        $response = $this->json(
            'POST',
            '/api/login',
            [
                'email' => $instructor->details->email,
                'password' => 'password',
            ]
        );

        $this->assertEquals(auth()->user()->first_name ?? null, $instructor->details->first_name);

        $response->assertStatus(200);



        // Instructor::unsetEventDispatcher();
        // $instructor = factory(Instructor::class)->create();

        // $response = $this->json(
        //     'POST',
        //     '/api/login',
        //     [
        //         'email' => $instructor->details->email,
        //         'password' => 'password',
        //     ]
        // );

        // $response->assertSee('invalid');

        // $response->assertStatus(422);
    }


    public function testAPasswordMustBeGreaterThanOrEqualto8 ()
    {
        $password = 'secret';
        $faker = Factory::create();
        $qualifications = $faker->sentence;
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'first_name' =>  $faker->firstName ,
                'last_name' =>   $faker->lastName,
                'qualifications' => $qualifications,
                'consideration' =>  $faker->paragraph,
                // 'profile_pic' => '',
                'other_name' => $faker->lastName,
                'email' => $faker->email,
                'as' => 'instructor',
                'phone_number' => $faker->phoneNumber,
                'password' => $password
            ]
        );

        // dd((array) $response->getContent());

        $response->assertJsonFragment([
            "errors" => [
                "password" => [
                    "The password must be at least 8 characters."
                ]
            ]
        ]);
    }


    public function testCannotRegisterAnInstructorWithoutCompletingAllRequiredFields ()
    {
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'as' => 'instructor'
            ]
        );


        $response->assertJsonFragment([
            "errors" => [
                "password" => [
                    "The password field is required."
                ],
                "phone_number" => [
                    "The phone number field is required."
                ],
                "email" => [
                    "The email field is required."
                ],
                "last_name" => [
                    "The last name field is required."
                ],
                "first_name" => [
                    "The first name field is required."
                ],
                "consideration" => [
                    "The consideration field is required."
                ],
                "qualifications" => [
                    "The qualifications field is required."
                ],
            ]
        ]);
    }

    public function testCannotRegisterWithMissingASFields ()
    {
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'phone_number' => '0290328739'
            ]
        );


        $response->assertJsonFragment([
            "errors" => [
                "as" => [
                    "The type of user is required"
                ]
            ]
        ]);
    }


    public function testPendingInstructorCannotLogin ()
    {
        // Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();
        $instructor->details->status = 'pending';
        $instructor->details->save();

        // dd($instructor->details);

        $response = $this->json(
            'POST',
            '/api/login',
            [
                'email' => $instructor->details->email,
                'password' => 'password'
            ]
        );

        $response->assertDontSee('logged you in');
        $response->assertStatus(422);
    }


    public function testCannotRegisterUsingSameExistingEmail1 ()
    {
        // Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

        $response = $this->json(
            'POST',
            '/api/register',
            [
                'email' => $instructor->details->email,
                'as' => 'instructor'
            ]
        );

        $response->assertSee("The email has already been taken.");

    }

    public function testCannotRegisterUsingSameExistingEmail2 ()
    {
        // Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

        $instructor->details->provider = 'facebook';
        $instructor->details->provider_id = '83792379283';
        $instructor->details->save();

        $response = $this->json(
            'POST',
            '/api/register',
            [
                'email' => $instructor->details->email,
                'as' => 'instructor'
            ]
        );

        $response->assertSee("The email has already been taken.");
    }


    public function testSendUserPasswordResetLinkValidation ()
    {
       $response = $this->json(
           'POST',
           '/api/password/email',
           []
       );

       $response->assertJsonFragment([
           'errors' => [
               'email' => [
                   'The email field is required.'
               ]
           ]
       ]);

    }

    public function testUserCanOnlyResetPasswordWhenItWasRegisteredManually ()
    {
        // Instructor::unsetEventDispatcher();
        $instructor = factory(Instructor::class)->create();

       $instructor->details->provider = 'facebook';
       $instructor->details->provider_id = '83792379283';
       $instructor->details->save();

       $response = $this->json(
           'POST',
           '/api/password/email',
           [
               'email' => $instructor->details->email
           ]
       );

       $response->assertJsonFragment([
           'errors' => [
               'email' => ['Unable to find user for the given email address.']
           ]
       ]);

        $response = $this->json(
            'POST',
            '/api/password/email',
            [
                'email' => 'jsdjk@sdjhk.com'
            ]
        );

        $response->assertJsonFragment([
            'errors' => [
                'email' => ['Unable to find user for the given email address.']
            ]
        ]);

        $response->assertStatus(422);
    }


    public function testUserCanOnlyResetPasswordWhenRegisteredManually ()
    {
       \Mail::fake();

    //    Instructor::unsetEventDispatcher();
       $instructor = factory(Instructor::class)->create();

       $response = $this->json(
           'POST',
           '/api/password/email',
           [
               'email' => $instructor->details->email
           ]
       );

    //    \Mail::assertSent();

       $response->assertJsonFragment([
           'message' => 'We have e-mailed your password reset link!'
       ]);
    }

    // public function testRedirectionWhenLoggedIn ()
    // {
    //     $user = factory(Instructor::class)->create();

    //     $this->actingAs($user)
    //          ->visits('/auth')
    //          ->see('what do you want to learn');
    // }
}
