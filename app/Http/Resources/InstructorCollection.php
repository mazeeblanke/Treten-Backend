<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\Paginator;

class InstructorCollection extends ResourceCollection
{
    // /**
    //  * The resource that this resource collects.
    //  *
    //  * @var string
    //  */
    // public $collects = User::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $payload = [
            'data' => $this->collection,
            'message' => 'Successfully fetched instructors',
            $this->mergeWhen($this instanceof Paginator, [
                "currentPage" => $this->currentPage(),
                "total" => $this->total(),
                "perPage" => $this->perPage(),
            ]),
        ];

        return $payload;
    }
}