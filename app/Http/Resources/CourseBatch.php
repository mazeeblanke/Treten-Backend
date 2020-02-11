<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class CourseBatch extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $timetable = isset($this->pivot) ? $this->pivot->timetable : $this->timetable;
        $courseBatchAuthorId = isset($this->pivot) ? $this->pivot->id : null;
        // dd($this->pivot);

        // dd(unserialize(unserialize($timetable)));
        // if (is_null($timetable))
        // {
        //     $timetable = [];
        // }
        // return [];
        // dd($this);
        return  [
            'id' => $this->when($this->id, $this->id),
            'batchName' => $this->when($this->batch_name, $this->batch_name),
            'commencementDate' => $this->when($this->start_date, $this->start_date),
            'friendlyCommencementDate' => $this->friendly_start_date,
            'modeOfDelivery' => $this->when($this->mode_of_delivery, $this->mode_of_delivery),
            'endDate' => $this->end_date,
            'price' => $this->price,
            'hasEnded' => $this->has_ended,
            'classIsFull' => $this->class_is_full,
            'authorId' => $this->when($this->author_id, $this->author_id),
            'courseId' => $this->when($this->course_id, $this->course_id),
            'timetable' => is_null($timetable) ? [] : unserialize($timetable),
            'courseBatchAuthorId' => $courseBatchAuthorId,
            'author' => new UserResource($this->whenLoaded('author')),
        ];
    }
}
