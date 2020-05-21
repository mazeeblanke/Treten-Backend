<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as RC;

class ResourceCollection extends RC
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
            'message' => 'Successfully fetched resources',
        ];

        if ($request->page) {
            return array_merge($payload, [
                "currentPage" => $this->currentPage(),
                "total" => $this->total(),
                "perPage" => $this->perPage(),
            ]);
        }

        return $payload;
    }
}