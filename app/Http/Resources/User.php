<?php

namespace App\Http\Resources;

use App\Http\Resources\Userable as UserableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'profilePic' => $this->profile_pic,
            'otherName' => $this->other_name,
            'phoneNumber' => $this->phone_number,
            'createdAt' => $this->friendly_created_at,
            'updatedAt' => $this->updated_at,
            'userableId' => $this->userable_id,
            'userableType' => $this->userable_type,
            'totalBatches' => $this->when($this->total_batches >= 0, $this->total_batches),
            'userable' => new UserableResource($this->whenLoaded('userable')),
            // 'userable' => $this->userable,
            'details' => new UserableResource($this->whenLoaded('details')),
            'msuuid' => new UserableResource($this->whenLoaded('msuuid')),
            'mruuid' => new UserableResource($this->whenLoaded('mruuid')),
            'status' => $this->status,
            'provider' => $this->provider,
            'providerId' => $this->provider_id,
            'name' => $this->name,
            'title' => $this->title,
            'gravatar' => $this->gravatar,
            'role' => $this->role,
        ];
        // return parent::toArray($request);
    }
}