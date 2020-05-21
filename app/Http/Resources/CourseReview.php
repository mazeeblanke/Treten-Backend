<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseReview extends JsonResource
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
            'approved' => $this->approved,
            'courseId' => $this->course_id,
            'createdAt' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
            'enrolleeId' => $this->enrollee_id,
            'rating' => $this->rating,
            'reviewText' => $this->review_text,
            'enrollee' => new User($this->whenLoaded('enrollee')),
            'course' => new Course($this->whenLoaded('course')),
        ];
    }
}
