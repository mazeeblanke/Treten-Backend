<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Testimonial extends JsonResource
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
            'reviewText' => $this->text,
            'profilePic' => $this->profile_pic,
            'role' => $this->role,
            'name' => $this->name
        ];
    }
}
