<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use Newsletter;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterSubscriptionTest extends TestCase
{
    protected static $email;
    protected static $faker;

    public static function setUpBeforeClass(): void/* The :void return type declaration that should be here would cause a BC issue */
    {
       static::$faker = Factory::create();
       static::$email = static::$faker->email;
    }

    /**
     *
     *
     * @return void
     */
    public function testCanSubscribeAUserEmailToMailingList()
    {
        $response = $this->json(
            'POST',
            '/api/newsletter/subscribe',
            [
                'email' => static::$email
            ]
        );

        $response->assertJson([
            'message' => 'Successfully added to the mailing list'
        ]);
        $response->assertStatus(200);
    }

    public function testCannotBeAddedToMaillistWithIncompleteInfo ()
    {
        $response = $this->json(
            'POST',
            '/api/newsletter/subscribe',
            []
        );

        $response->assertJsonFragment([
            'errors' => [
                'email' => [
                    'The email field is required.'
                ]
            ]
        ]);

        $response->assertStatus(422);
    }

    /**
     *
     *
     * @return void
     */
    public function testCannotSubscribeAUserEmailToMailingListTwice()
    {
        $response = $this->json(
            'POST',
            '/api/newsletter/subscribe',
            [
                'email' => static::$email
            ]
        );

        $response->assertJson([
            'message' => 'You are already on the mailing list'
        ]);
        $response->assertStatus(200);
    }



    /**
     *
     *
     * @return void
     */
    public function testCanUnsubscribeAUserEmailFromMailingList()
    {
        $response = $this->json(
            'POST',
            '/api/newsletter/unsubscribe',
            [
                'email' => static::$email
            ]
        );

        $response->assertJson([
            'message' => 'You have been removed from the mailing list'
        ]);
        $response->assertStatus(200);
    }

    /**
     *
     *
     * @return void
     */
    public function testCannotUnsubscribeAnUnsubscribedEmailFromMailingList()
    {
        $response = $this->json(
            'POST',
            '/api/newsletter/unsubscribe',
            [
                'email' => static::$email
            ]
        );

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'You are not on the mailing list'
        ]);
    }


    // /**
    //  *
    //  *
    //  * @return void
    //  */
    // public function testCannotResubscribeAUserEmailToMailingList()
    // {
    //     $response = $this->json(
    //         'POST',
    //         '/api/newsletter/subscribe',
    //         [
    //             'email' => static::$email
    //         ]
    //     );

    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         'message' => 'Could not add to the mailing list'
    //     ]);
    // }

}
