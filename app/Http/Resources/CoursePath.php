<?php

namespace App\Http\Resources;

use App\Http\Resources\Course;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursePath extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'bannerImage' => $this->banner_image,
            'courses' => Course::collection($this->whenLoaded('courses')),
        ];
    }
}