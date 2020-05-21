<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Instructor extends JsonResource
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
            'consideration' => $this->consideration,
            'qualifications' => $this->qualifications,
            'socialLinks' => $this->social_links,
            'title' => $this->title,
            'workExperience' => $this->work_experience,
            'education' => $this->education,
            'certifications' => $this->certifications,
            'bio' => $this->bio,
            'timetable' => $this->timetable,
            // 'details' => $this->whenLoaded($this->details, $this->details),
        ];
    }
}