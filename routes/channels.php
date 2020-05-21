<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Message;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('doneChannel', function () {
//     return true;
// });

// Broadcast::channel("user.{receiver_id}", function ($user, $id) {
//     return true;
//         // return (int) $user->id === (int) $id;
// });

Broadcast::channel("treten_database_private-user.{receiverorsender_id}", function ($user, $receiverorsender_id) {
    return (int) $user->id === (int) $receiverorsender_id;
});

// Broadcast::channel("treten_database_private-user.{receiverorsender_id}", function ($user, $receiverorsender_id) {
//     return (int) $user->id === (int) $receiverorsender_id;
// });

// Broadcast::channel("treten_database_user.1", function ($user, $receiverorsender_id) {
//     // return (int) $user->id === (int) $receiverorsender_id;
//     return true;
//         // return (int) $user->id === (int) $id;
// });

// Broadcast::channel("treten_database-chat.*", function ($user, $message_uuid) {
//     $message = Message::where('message_uuid', $message_uuid)->first();
//     if (!$message) return true;

//     return $message->sender_id === 10;
//     return (int) $message->sender_id === (int) $user->id ?? (int) $message->receiver_id === (int) $user->id;
//     // return true;
//         // return (int) $user->id === (int) $id;
// });