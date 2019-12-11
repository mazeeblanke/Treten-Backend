<?php

namespace App\Http\Resources;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserGroupCollection extends ResourceCollection
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
            'message' => 'Successfully fetched user groups',
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
