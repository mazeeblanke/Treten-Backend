<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Enrollment extends JsonResource
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
            'id' => $this->when($this->id, $this->id),
            'active' => $this->active,
            'status' => $this->active ? 'active' : 'pending',
            'courseId' => $this->course_id,
            'courseBatchId' => $this->course_batch_id,
            'createdAt' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y g:i A'),
            'userId' => $this->user_id,
            'active' => $this->active,
            'user' => new User($this->whenLoaded('user')),
            'course' => new Course($this->whenLoaded('course')),
        ];
    }
}
