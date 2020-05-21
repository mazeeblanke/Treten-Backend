<?php

namespace Tests\Feature;

use App\User;
use App\Student;
use Faker\Factory;
use Tests\TestCase;
use App\Mail\ContactUsMail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

        $payload = [
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $faker->email,
            'phoneNumber' => $faker->phoneNumber,
            'message' => $faker->sentence
            // 'user_id' => ''
        ];

        $response = $this->json(
            'POST',
            '/api/contactus',
            $payload
        );

        $response->assertJson([
            'message' => 'successfully contacted treten!'
        ]);

        $this->assertDatabaseHas('contact_us', [
            'first_name' => $payload['firstName'],
            'last_name' => $payload['lastName'],
            'email' => $payload['email'],
            'phone_number' => $payload['phoneNumber'],
            'message' => $payload['message']
        ]);

        $response->assertStatus(200);
    }


    public function testAUserCanSubmitTheContactUsForm()
    {

        \Mail::fake();

        $student = factory(Student::class)->create();

        $faker = Factory::create();

        $payload = [
            'firstName' => $student->details->first_name,
            'lastName' => $student->details->last_name,
            'email' => $student->details->email,
            'phoneNumber' => $faker->phoneNumber,
            'message' => $faker->sentence,
            'userId' => $student->details->id
        ];

        $response = $this
        ->actingAs($student->details)
        ->json(
            'POST',
            '/api/contactus',
            $payload
        );


        $response->assertJson([
            'message' => 'successfully contacted treten!'
        ]);

        $this->assertDatabaseHas('contact_us', [
            'first_name' => $payload['firstName'],
            'last_name' => $payload['lastName'],
            'email' => $payload['email'],
            'phone_number' => $payload['phoneNumber'],
            'message' => $payload['message'],
            'user_id' => $payload['userId']
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $student->details->id
        ]);

        // \Mail::assertQueued(ContactUsMail::class, 1);

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
                'firstName' => [
                    'The first name field is required.'
                ],
                'lastName' => [
                    'The last name field is required.'
                ],
                'email' => [
                    'The email field is required.'
                ],
                'phoneNumber' => [
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
