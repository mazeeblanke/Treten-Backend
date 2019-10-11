<?php

namespace Tests\Feature;

use App\User;
use App\Student;
use Faker\Factory;
use Tests\TestCase;
use App\Mail\ContactUsMail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactUsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAGuestCanSubmitTheContactUsForm()
    {
        $faker = Factory::create();
        $phone_number = $faker->phoneNumber;
        $message = $faker->sentence;
        $first_name = $faker->firstName;
        $last_name = $faker->lastName;
        $email= $faker->email;

        $response = $this->json(
            'POST',
            '/api/contactus',
            [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone_number' => $phone_number,
                'message' => $message
                // 'user_id' => ''
            ]
        );

        $response->assertJson([
            'message' => 'successfully contacted treten!'
        ]);

        $this->assertDatabaseHas('contact_us', [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'message' => $message
        ]);

        $response->assertStatus(200);
    }


    public function testAUserCanSubmitTheContactUsForm()
    {

        // \Mail::fake();

        Student::unsetEventDispatcher();
        $student = factory(Student::class)->create();

        $faker = Factory::create();
        $phone_number = $faker->phoneNumber;
        $message = $faker->sentence;

        $response = $this->json(
            'POST',
            '/api/contactus',
            [
                'first_name' => $student->details->first_name,
                'last_name' => $student->details->last_name,
                'email' => $student->details->email,
                'phone_number' => $phone_number,
                'message' => $message,
                'user_id' => $student->details->id
            ]
        );
        

        $response->assertJson([
            'message' => 'successfully contacted treten!'
        ]);

        $this->assertDatabaseHas('contact_us', [
            'first_name' => $student->details->first_name,
            'last_name' => $student->details->last_name,
            'email' => $student->details->email,
            'phone_number' => $phone_number,
            'message' => $message,
            'user_id' => $student->details->id
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $student->details->id
        ]);

        // \Mail::assertSent(ContactUsMail::class, 1);

        $response->assertStatus(200);
    }


    public function testAUserCannotSubmitTheContactUsFormWhenMissingInput ()
    {
        $response = $this->json(
            'POST',
            '/api/contactus',
            []
        );

        $response->assertJsonFragment([
            'errors' => [
                'first_name' => [
                    'The first name field is required.'
                ],
                'last_name' => [
                    'The last name field is required.'
                ],
                'email' => [
                    'The email field is required.'
                ],
                'phone_number' => [
                    'The phone number field is required.'
                ],
                'message' => [
                    'The message field is required.'
                ]
            ]
        ]);

        // $this->assertDatabaseDoesNotHave ();

        $response->assertStatus(422);
    }
}
