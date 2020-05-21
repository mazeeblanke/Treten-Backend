<?php

namespace App\Http\Controllers;

use App\Events\MessageReceived;
use App\Http\Controllers\Controller;
use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\MessageCollection;
use App\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // If user not auth eject
        if (!auth()->check()) {
            return response()->json([
                'message' => "Error Processing Request, you are not signed in",
            ], 422);
        }

        // Fix issue with senderid === reciever-d
        $pageSize = $request->pageSize ?? 6;
        $page = $request->page ?? 1;
        $type = $request->type ?? 'chat';

        if ($type === 'chat') {
            $sub = Message::select(
                'messages.message_uuid',
                \DB::raw('MAX(messages.id) as id')
            )
                ->where('messages.message_type', $type)
                ->where(function ($query) {
                    return $query
                        ->orWhere('sender_id', auth()->user()->id)
                        ->orWhere('receiver_id', auth()->user()->id);
                })
                ->groupBy('messages.message_uuid');

            $messages = Message::select('*')
                ->with(['receiver', 'sender'])
                ->latest()
                ->joinSub($sub, 'm', function ($join) {
                    $join->on('messages.id', '=', 'm.id');
                })
                ->paginate($pageSize, '*', 'page', $page);
        }

        if ($type === 'broadcast') {
            $messageIds = Message::where('messages.message_type', $type)
                ->where(function ($query) {
                    return $query
                        ->orWhere('messages.sender_id', auth()->user()->id)
                        ->orWhere('messages.receiver_id', auth()->user()->id);
                })
                ->select(\DB::raw('max(messages.id) as d'))
                ->groupBy('messages.sender_id', 'messages.title')
                ->get()->pluck('d')->toArray();

            $messages = Message::select('*')
                ->with(['receiver', 'sender', 'group'])
                ->whereIn('id', $messageIds)
                ->latest()
                ->paginate($pageSize, '*', 'page', $page);
        }

        return response()->json(new MessageCollection($messages));
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

        // //fetch request data
        $lastMsg = Message::
            orWhere(function ($query) use ($request) {
            return $query
                ->where('sender_id', auth()->user()->id)
                ->where('receiver_id', $request->receiver_id);
        })
            ->orWhere(function ($query) use ($request) {
                return $query
                    ->where('receiver_id', auth()->user()->id)
                    ->where('sender_id', $request->receiver_id);
            })
            ->first();

        $message = Message::create([
            "sender_id" => auth()->user()->id,
            "hash" => $request->hash,
            "receiver_id" => $request->receiver_id,
            "message" => $request->message,
            'message_uuid' => (\is_null($lastMsg) && $request->type != 'broadcast') || $request->type == 'broadcast'
            ? \Str::uuid()
            : $lastMsg->message_uuid,
            'message_type' => $request->type ?? 'chat',
            'title' => $request->title,
        ]);

        // broadcast to the user/receiver
        event(new MessageReceived($message->load(['sender', 'receiver'])));
        return response()->json([
            'message_data' => new MessageResource($message),
        ]);
        // retunr 200
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $message_uuid)
    {
        $pageSize = $request->pageSize ?? 6;
        $page = $request->page ?? 1;

        $messages = Message::where('message_uuid', $message_uuid)
            ->with(['receiver', 'sender'])
            ->latest()
            ->paginate($pageSize, '*', 'page', $page);
        // ->toArray();

        return response()->json(new MessageCollection($messages));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}