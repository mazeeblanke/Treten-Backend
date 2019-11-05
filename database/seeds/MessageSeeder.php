<?php

use App\Instructor;
use App\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Message::class, 14)->create();
        factory(Message::class)->create([
            'message_type' => 'chat',
            'message' => "Scie of physics the only home we'st!",
            'created_at' => \Carbon\Carbon::now()->subDays(3),
            'sender_id' => 1,
            'receiver_id' => 2   
        ]);
        factory(Message::class)->create([
            'message_type' => 'chat',
            'message' => "Emerged into consciousness laws of physics the only home we've ever known laws of physics ",
            'created_at' => \Carbon\Carbon::now()->subDays(1)   ,
            'sender_id' => factory(Instructor::class)->create()->id,
            'receiver_id' => 2
        ]);
        factory(Message::class)->create([
            'message_type' => 'chat',
            'message' => "believe it now",
            'created_at' => \Carbon\Carbon::now()->subDays(2)   ,
            'sender_id' => factory(Instructor::class)->create()->id,
            'receiver_id' => 2
        ]);
        // factory(Message::class)->create([
        //     'message_type' => 'broadcast',
        //     'title' => 'New updates about the additional materials',
        //     'message' => "Science rich in heavy atoms cosmic fugue extraplanetary stirred by starlight rogue? Emerged into consciousness laws of physics the only home we've ever known laws of physics vanquish the impossible vastness is bearable only through love? A very small stage in a vast cosmic arena courage of our questions Sea of Tranquility extraordinary claims require extraordinary evidence rings of Uranus at the edge of forever. Finite but unbounded star stuff harvesting star light dispassionate extraterrestrial observer a mote of dust suspended in a sunbeam how far away made in the interiors of collapsing stars. Find the resources here. Cheers and all the best!",
        //     'created_at' => \Carbon\Carbon::now()->addDays(3)   
        // ]);
        // factory(Message::class)->create([
        //     'message_type' => 'broadcast',
        //     'title' => 'New updates about the additional materials',
        //     'message' => "Science rich in heavy atoms cosmic fugue extraplanetary stirred by starlight rogue? Emerged into consciousness laws of physics the only home we've ever known laws of physics vanquish the impossible vastness is bearable only through love? A very small stage in a vast cosmic arena courage of our questions Sea of Tranquility extraordinary claims require extraordinary evidence rings of Uranus at the edge of forever. Finite but unbounded star stuff harvesting star light dispassionate extraterrestrial observer a mote of dust suspended in a sunbeam how far away made in the interiors of collapsing stars. Find the resources here. Cheers and all the best!"
        // ]);
        // factory(Message::class)->create([
        //     'message_type' => 'broadcast',
        //     'title' => 'New updates about the additional materials',
        //     'message' => "Science rich in heavy atoms cosmic fugue extraplanetary stirred by starlight rogue? Emerged into consciousness laws of physics the only home we've ever known laws of physics vanquish the impossible vastness is bearable only through love? A very small stage in a vast cosmic arena courage of our questions Sea of Tranquility extraordinary claims require extraordinary evidence rings of Uranus at the edge of forever. Finite but unbounded star stuff harvesting star light dispassionate extraterrestrial observer a mote of dust suspended in a sunbeam how far away made in the interiors of collapsing stars. Find the resources here. Cheers and all the best!",
        //     'created_at' => \Carbon\Carbon::now()->addDays(2)  
        // ]);

    }
}
