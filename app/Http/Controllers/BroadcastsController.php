<?php

namespace App\Http\Controllers;

use App\Events\BroadcastReceived;
use App\Http\Resources\Message as ResourcesMessage;
use App\Message;
use App\UserGroup;
use App\UserGroupAllocation;
use Illuminate\Http\Request;

class BroadcastsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If user not auth eject
        if (!auth()->check()) {
            return response()->json([
                'message' => "Error Processing Request, you are not signed in",
            ], 422);
        }

        // // //fetch request data
        // $lastMsg = Message::
        //     orWhere(function ($query) use ($request) {
        //     return $query
        //         ->where('sender_id', auth()->user()->id)
        //         ->where('receiver_id', $request->receiver_id);
        // })
        //     ->orWhere(function ($query) use ($request) {
        //         return $query
        //             ->where('receiver_id', auth()->user()->id)
        //             ->where('sender_id', $request->receiver_id);
        //     })
        //     ->first();

        // $now = Carbon::now('utc')->toDateTimeString();
        // $messages = [];
        // $data = array(
        //     array(
        //         'name'=>'Coder 1', 'rep'=>'4096',
        //         'created_at'=> $now,
        //         'updated_at'=> $now
        //     ),
        //     array(
        //         'name'=>'Coder 2', 'rep'=>'2048',
        //         'created_at'=> $now,
        //         'updated_at'=> $now
        //     ),
        //     //...
        // );
        $targetGroup = UserGroup::whereId($request->userGroupId)->first();

        if (!$targetGroup) {
            return response()->json([
                'message' => "Error Processing Request, user group does not exist",
            ], 422);
        }

        $targetUsers = UserGroupAllocation::whereUserGroupId($targetGroup->id)->get();

        if (!count($targetUsers))
        {
            return response()->json([
                'message' => "Error Processing Request, user group is empty",
            ], 422);
        }

        $messages = $targetUsers->map(function ($targetUser) use ($targetGroup) {
            return [
                "sender_id" => auth()->user()->id,
                "hash" => request()->hash ?? '',
                "receiver_id" => $targetUser->user_id,
                "message" => request()->message,
                'message_uuid' => \Str::uuid(),
                'message_type' => request()->type ?? 'broadcast',
                'title' => request()->title,
                'group_id' => $targetGroup->id,
                'created_at'=> now(),
                'updated_at'=> now(),
            ];
        });

        if (Message::insert($messages->toArray()))
        {
            $messageUuids = $messages->map(function ($message) {
                return $message['message_uuid'];
            });
    
            $messages = Message::with(['sender', 'receiver'])->whereIn('message_uuid', $messageUuids)->get();
    
            $messages->each(function ($message) {
                // broadcast to the user/receiver
                event(new BroadcastReceived($message));
            });
    
            return response()->json([
                'message' => 'Successfully sent messages'
                // 'message_data' => new ResourcesMessage($message),
            ]);
        }

        return response()->json([
            'message' => 'Ann error occurred!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
