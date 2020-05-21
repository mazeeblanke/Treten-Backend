<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'messageUuid' => $this->message_uuid,
            'hash' => $this->hash,
            'title' => $this->title,
            'read' => $this->read,
            'formattedDate' => $this->formatted_date,
            'sender' => new UserResource($this->whenLoaded('sender')),
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            'group' => new UserGroup($this->whenLoaded('group')),
            'senderId' => $this->sender_id,
            'receiverId' => $this->receiver_id,
            'messageType' => $this->message_type,
        ];
    }
}