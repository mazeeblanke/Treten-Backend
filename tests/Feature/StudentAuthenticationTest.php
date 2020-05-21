<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use App\User;
use App\Student;
use App\Instructor;

class StudentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAStudentCanSignUp()
    {
        $password = '12345678';
        $faker = Factory::create();
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $otherName = $faker->lastName;
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'first_name' => $firstName,
                'last_name' =>   $lastName,
                // 'profile_pic' => '',
                'other_name' => $otherName,
                'email' => $faker->email,
                'as' => 'student',
                'phone_number' => $faker->phoneNumber,
                'password' => $password
            ]
        );

        // dd($response->getContent());

        $users = User::whereFirstName($firstName)->whereLastName($lastName)->get();

        $this->assertCount(1, $users);

        $response->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAStudentCanLogin()
    {
        Student::unsetEventDispatcher();
        $student = factory(Student::class)->create();

        $response = $this->json(
            'POST',
            '/api/login',
            [
                'email' => $student->details->email,
                'password' => 'password',
            ]
        );

        $this->assertEquals(auth()->user()->first_name ?? null, $student->details->first_name);

        $response->assertStatus(200);
    }


    public function testCannotRegisterAStudentWithoutCompletingAllRequiredFields ()
    {
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'as' => 'student'
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
            ]
        ]);
    }


    public function testAPasswordMustBeGreaterThanOrEqualto8 ()
    {
        $password = 'secret';
        $faker = Factory::create();
        $response = $this->json(
            'POST',
            '/api/register',
            [
                'first_name' =>  $faker->firstName ,
                'last_name' =>   $faker->lastName,
                // 'profile_pic' => '',
                'other_name' => $faker->lastName,
                'email' => $faker->email,
                'as' => 'student',
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


    // public function testRedirectionWhenLoggedIn ()
    // {
    //     Student::unsetEventDispatcher();
    //     $student = factory(Student::class)->create();

    //     $this->actingAs($student->details)
    //          ->followingRedirects()
    //          ->get('/auth')
    //          ->assertRedirect('/');
    //         //  ->assertSee('Redirecting');
    // }

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
       Student::unsetEventDispatcher();
       $student = factory(Student::class)->create();

       $student->details->provider = 'facebook';
       $student->details->provider_id = '83792379283';
       $student->details->save();

       $response = $this->json(
           'POST',
           '/api/password/email',
           [
               'email' => $student->details->email
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

       Student::unsetEventDispatcher();
       $student = factory(Student::class)->create();

       $response = $this->json(
           'POST',
           '/api/password/email',
           [
               'email' => $student->details->email
           ]
       );

    //    \Mail::assertSent();

       $response->assertJsonFragment([
           'message' => 'We have e-mailed your password reset link!'
       ]);
    }

    // TODO: Add selenium driver for this later
    // public function testUserIsRedirectedTofacebookForSocialLogin ()
    // {
    //     $this->followingRedirects()->get('/auth/facebook')->assertSee('Log in to Facebook');
    // }
}
