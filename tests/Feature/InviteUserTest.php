<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InviteUserTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;

    /**
     * Test invitation where an email has alreadybeen invited for a role
     *
     * @return void
     */
    public function testInvitationWhereAnEmailHasAlreadyBeenInvitedForARole()
    {
        \DB::table('user_invitations')->insert([
            'email' => 'm@y.com',
            'token' => 'jsjsjsjsjsjsjsjsjsjsjsjsjsjwj',
            'role' => 'instructor'
        ]);
        $payload = [
            'emails' => [
                'm@y.com',
                'l@y.com',
                // 'p@uy.com'
            ],
            'role' => 'instructor'
        ];
        $response = $this->json(
            'POST',
            '/api/invite-users',
            $payload
        );

        // dd($response->getContent());


        $this->assertDatabaseHas('user_invitations', [
            'email' => 'm@y.com',
            'token' => 'jsjsjsjsjsjsjsjsjsjsjsjsjsjwj',
            'role' => 'instructor'
        ]);

        $this->assertDatabaseHas('user_invitations', [
            'email' => 'l@y.com',
            'role' => 'instructor'
        ]);

        // $this->assertDatabaseHas('user_invitations', [
        //     'email' => 'p@uy.com',
        //     'role' => 'instructor'
        // ]);

        $response->assertStatus(200);
    }

    /**
     * Test invitation where an email has already been invited but for a different role
     *
     * @return void
     */
    public function testInvitationValidation()
    {
        $payload = [
            'emails' => [
                'sddj'
            ],
            'role' => 'admin'
        ];
        $response = $this->json(
            'POST',
            '/api/invite-users',
            $payload
        );

        $response->assertSee("The email is not valid");

        $payload = [
            'emails' => 'sdd',
            'role' => 'admin'
        ];

        $response = $this->json(
            'POST',
            '/api/invite-users',
            $payload
        );

        $response->assertSee("The emails field must be an array.");

        $payload = [
            'emails' => [
                'j@you.com',
                'j@you.com',
                'j@you.com',
            ],
            'role' => 'admin'
        ];

        $response = $this->json(
            'POST',
            '/api/invite-users',
            $payload
        );

        // dd($response->getContent());
        $response->assertSee("This email is a duplicate");

        $user = factory(\App\User::class)->create([
            'email' => 'mazino@yahoo.com'
        ]);

        $payload = [
            'emails' => [
                'j@you.com',
                'mazino@yahoo.com',
                'm@y.com',
            ],
            'role' => 'admin'
        ];

        $response = $this->json(
            'POST',
            '/api/invite-users',
            $payload
        );
;
        $response->assertSee("This email has already been taken");


        $response->assertStatus(422);
    }

    // /**
    //  * Test invitation where an email has already been invited but for a different role
    //  *
    //  * @return void
    //  */
    // public function testInvitationWhereAnEmailHasAlreadyBeenInvitedButForADifferentRole()
    // {
    //     \DB::table('user_invitations')->insert([
    //         'email' => 'm@y.com',
    //         'token' => 'jsjsjsjsjsjsjsjsjsjsjsjsjsjwj',
    //         'role' => 'instructor'
    //     ]);
    //     $payload = [
    //         'emails' => [
    //             'm@y.com',
    //             // 'l@y.com',
    //             // 'p@uy.com'
    //         ],
    //         'role' => 'admin'
    //     ];
    //     $response = $this->json(
    //         'POST',
    //         '/api/invite-users',
    //         $payload
    //     );

    //     // dd($response->getContent());

    //     $this->assertDatabaseMissing('user_invitations', [
    //         'email' => 'm@y.com',
    //         'token' => 'jsjsjsjsjsjsjsjsjsjsjsjsjsjwj',
    //         'role' => 'instructor'
    //     ]);

    //     $this->assertDatabaseHas('user_invitations', [
    //         'email' => 'm@y.com',
    //         'role' => 'admin'
    //     ]);

    //     // $this->assertDatabaseHas('user_invitations', [
    //     //     'email' => 'l@y.com',
    //     //     'role' => 'admin'
    //     // ]);

    //     // $this->assertDatabaseHas('user_invitations', [
    //     //     'email' => 'p@uy.com',
    //     //     'role' => 'admin'
    //     // ]);

    //     $response->assertStatus(200);
    // }
}
