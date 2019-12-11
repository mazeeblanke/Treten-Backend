<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Timetable extends JsonResource
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
            'bio' => $this->bio,
            'email' => $this->email,
            'title' => $this->title,
            'active' => $this->active, 
            'userId' => $this->user_id,
            'studentid' => $this->user_id,
            'lastName' => $this->last_name,
            'courseId' => $this->course_id, 
            'authorId' => $this->author_id,
            'education' => $this->education,
            'timetable' => $this->timetable,
            'firstName' => $this->first_name,
            'otherName' => $this->other_name,
            'startDate' => \Carbon\Carbon::parse($this->start_date)->format('D, d M Y'),
            'userableId' => $this->userable_id,
            'profilePic' => \Storage::url($this->profile_pic) . '?=' . time(),
            'socialLinks' => unserialize($this->social_links),
            'phoneNumber' => $this->phone_number,
            'consideration' => $this->consideration,
            'qualifications' => $this->qualifications,
            'instructorSlug' => \Str::slug("{$this->first_name} {$this->last_name} {$this->id}", '_'),
            'courseBatchId' => $this->course_batch_id,
            'certifications' => $this->certifications,
            'workExperience' => $this->work_experience,
            'name' => "{$this->first_name} {$this->last_name}",
         ];
    }
}
