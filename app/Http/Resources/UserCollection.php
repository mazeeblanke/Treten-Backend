<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
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
            $this->mergeWhen($request->page, [
                "currentPage" => $this->currentPage(),
                "total" => $this->total(),
                "perPage" => $this->perPage(),
            ]),
        ];

        return $payload;
    }
}