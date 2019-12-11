<?php

namespace Tests\Feature;

use App\Events\MessageReceived;
use App\Instructor;
use App\Message;
use App\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanFetchMessageThread()
    {
        Instructor::unsetEventDispatcher();
        Student::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        factory(Instructor::class, 20)->create();
        factory(Student::class, 20)->create();
        // $message1 = factory(Message::class)->create([
        //     'sender_id' => $john->details->id,
        //     'receiver_id' => $john->details->id
        // ]);
        $message2 = factory(Message::class)->create([
            'sender_id' => 21,
            'receiver_id' => $john->details->id,
            'message_type' => 'chat',
            'message' => 'first_chat, first one',
            'created_at' => \Carbon\Carbon::now()->addDays(1),
        ]);
        $message21 = factory(Message::class)->create([
            'sender_id' => 21,
            'receiver_id' => $john->details->id,
            'message_type' => 'broadcast',
            'message' => 'broadcast1',
            'created_at' => \Carbon\Carbon::now()->addDays(5),
        ]);
        $message212 = factory(Message::class)->create([
            'sender_id' => 212,
            'receiver_id' => $john->details->id,
            'message_type' => 'broadcast',
            'message' => 'broadcast2',
            'created_at' => \Carbon\Carbon::now()->addDays(10),
        ]);
        $message2134 = factory(Message::class)->create([
            'sender_id' => 21,
            'receiver_id' => $john->details->id,
            'message_type' => 'broadcast',
            'message' => 'broadcast4',
            'created_at' => \Carbon\Carbon::now()->addDays(90),
        ]);
        $message213 = factory(Message::class)->create([
            'sender_id' => 21,
            'receiver_id' => $john->details->id,
            'message_type' => 'broadcast',
            'message' => 'broadcast3',
            'created_at' => \Carbon\Carbon::now()->addDays(100),
        ]);
        $message3 = factory(Message::class)->create([
            'sender_id' => 41,
            'receiver_id' => $john->details->id,
            'message_type' => 'chat',
            'message' => 'firstone',
            'message_uuid' => 'message_uuid',
            'created_at' => \Carbon\Carbon::now()->subDays(2),
        ]);
        $message4 = factory(Message::class)->create([
            'sender_id' => 41,
            'receiver_id' => $john->details->id,
            'message_type' => 'chat',
            'message' => 'secondone',
            'message_uuid' => 'message_uuid',
            'created_at' => \Carbon\Carbon::now()->addDays(2),
        ]);

        $page = 1;
        $pageSize = 10;
        $response = $this
            ->actingAs($john->details)
            ->json(
                'GET',
                "/api/messagethread?type=chat&page=$page&pageSize=$pageSize"
            );

        $response->assertSee($message2->message);
        $response->assertSee($message4->message);
        $response->assertDontSee($message3->message);
        $response->assertStatus(200);

        $page = 2;
        $pageSize = 1;
        $response = $this
            ->actingAs($john->details)
            ->json(
                'GET',
                "/api/messagethread?type=broadcast&page=$page&pageSize=$pageSize"
            );
        $response->assertSee($message2134->message);
        $response->assertDontSee($message212->message);
        $response->assertDontSee($message21->message);
        $response->assertDontSee($message213->message);
        $response->assertStatus(200);

        $page = 1;
        $pageSize = 10;
        $response = $this
            ->actingAs($john->details)
            ->json(
                'GET',
                "/api/messagethread?type=broadcast&page=$page&pageSize=$pageSize"
            );
        $response->assertSee($message212->message);
        $response->assertSee($message2134->message);
        $response->assertSee($message21->message);
        $response->assertSee($message213->message);
        $response->assertStatus(200);

    }

    public function testCanFetchASingleMessageThread()
    {

        Instructor::unsetEventDispatcher();
        Student::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        factory(Instructor::class, 20)->create();
        factory(Student::class, 20)->create();
        // $message1 = factory(Message::class)->create([
        //     'sender_id' => $john->details->id,
        //     'receiver_id' => $john->details->id
        // ]);
        $message3 = factory(Message::class)->create([
            'sender_id' => 41,
            'receiver_id' => $john->details->id,
            'message_type' => 'chat',
            'message' => 'firstone',
            'created_at' => \Carbon\Carbon::now()->addDays(1),
        ]);
        $message4 = factory(Message::class)->create([
            'sender_id' => 41,
            'receiver_id' => $john->details->id,
            'message_type' => 'chat',
            'message' => 'secondone',
            'message_uuid' => $message3->message_uuid,
            'created_at' => \Carbon\Carbon::now()->addDays(2),
        ]);
        $message40 = factory(Message::class)->create([
            'sender_id' => $john->details->id,
            'receiver_id' => 41,
            'message_type' => 'chat',
            'message' => 'thirdone',
            'message_uuid' => $message3->message_uuid,
            'created_at' => \Carbon\Carbon::now()->addDays(3),
        ]);
        $message41 = factory(Message::class)->create([
            'sender_id' => 41,
            'receiver_id' => $john->details->id,
            'message_type' => 'chat',
            'message' => 'fourthone',
            'message_uuid' => $message3->message_uuid,
            'created_at' => \Carbon\Carbon::now()->addDays(4),
        ]);

        $page = 1;
        $pageSize = 1;
        $response = $this
            ->actingAs($john->details)
            ->json(
                'GET',
                "/api/messagethread/{$message3->message_uuid}?page=$page&pageSize=$pageSize"
            );

        $response->assertSee($message41->message);
        $response->assertDontSee($message40->message);
        $response->assertDontSee($message4->message);
        $response->assertDontSee($message3->message);
        $response->assertStatus(200);

        $page = 3;
        $pageSize = 1;
        $response = $this
            ->actingAs($john->details)
            ->json(
                'GET',
                "/api/messagethread/{$message3->message_uuid}?page=$page&pageSize=$pageSize"
            );
        $response->assertSee($message4->message);
        $response->assertDontSee($message40->message);
        $response->assertDontSee($message41->message);
        $response->assertDontSee($message3->message);
        $response->assertStatus(200);

        $page = 4;
        $pageSize = 1;
        $response = $this
            ->actingAs($john->details)
            ->json(
                'GET',
                "/api/messagethread/{$message3->message_uuid}?page=$page&pageSize=$pageSize"
            );
        $response->assertSee($message3->message);
        $response->assertDontSee($message40->message);
        $response->assertDontSee($message41->message);
        $response->assertDontSee($message4->message);
        $response->assertStatus(200);
    }

    // public function testCanSendBroadcastToAllUsers()
    // {

    // }

    public function testCanSendChatToAnotherUser()
    {

        \Event::fake();

        Instructor::unsetEventDispatcher();

        $john = factory(Instructor::class)->create();
        $john->details->status = 'active';
        $john->details->first_name = 'john';
        $john->details->save();

        $peter = factory(Instructor::class)->create();
        $peter->details->status = 'active';
        $peter->details->first_name = 'peter';
        $peter->details->save();

        $firstMessage = "whats up bro?";
        $secondMessage = "I am at you gate";

        $response = $this
            ->actingAs($john->details)
            ->json(
                'POST',
                '/api/messages',
                [
                    'receiver_id' => $peter->details->id,
                    'message' => $firstMessage,
                    'hash' => 'hash1'
                ]
            );

        $response->assertStatus(200);

        $f1 = Message::all()->first();

        \Event::assertDispatched(MessageReceived::class, function ($e) use ($f1) {
            return $e->message->id === $f1->id;
        });

        $response = $this
            ->actingAs($john->details)
            ->json(
                'POST',
                '/api/messages',
                [
                    'receiver_id' => $peter->details->id,
                    'message' => $secondMessage,
                    'hash' => 'hash'
                    // "type" => 'chat'
                ]
            );

        $response->assertStatus(200);

        $this->assertDatabaseHas('messages', [
            'receiver_id' => $peter->details->id,
            'sender_id' => $john->details->id,
            'message' => $firstMessage,
            'hash' => 'hash1',
            "message_uuid" => $f1->message_uuid,
            "message_type" => 'chat',
        ]);
        $this->assertDatabaseHas('messages', [
            'receiver_id' => $peter->details->id,
            'sender_id' => $john->details->id,
            'message' => $secondMessage,
            "message_uuid" => $f1->message_uuid,
            "message_type" => 'chat',
        ]);

        \Event::assertDispatched(MessageReceived::class, 2);
    }

    public function testMustBeLoggedInToUseMessaging()
    {
        $response = $this
            ->json(
                'GET',
                "/api/messagethread"
            );

        $response->assertStatus(422);

        $response = $this
            ->json(
                'POST',
                '/api/messages',
                [
                    'receiver_id' => 4,
                    'message' => 2,
                ]
            );

        $response->assertStatus(422);
    }
}