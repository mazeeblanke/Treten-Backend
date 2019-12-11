<?php

namespace App\Http\Resources;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
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
           'file' => $this->file,
           'title' => $this->title, 
           'authorId' => $this->author->id,
           'downloadLink' => $this->download_link,
           'summary' => $this->summary, 
           'courseId' => $this->course_id, 
           'authorId' => new UserResource($this->whenLoaded('author')),
           'course' => $this->whenLoaded('course'),
        ];
    }
}