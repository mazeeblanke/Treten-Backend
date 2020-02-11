<?php

namespace App\Http\Controllers;

use App\Filters\ResourceCollectionFilters;
use App\Http\Requests\ListResourceRequest;
use App\Http\Resources\Resource as ApiResource;
use App\Http\Resources\ResourceCollection;
use App\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListResourceRequest $request, ResourceCollectionFilters $filters)
    {
        // //validate/check permission to see anothe author stuff
        return response()->json(
            new ResourceCollection(
                Resource::filterUsing($filters)
                    ->orderBy(
                        'resources.created_at',
                        $request->sort ?? 'desc'
                    )
                    ->paginate(
                        $request->pageSize ?? 8,
                        '*',
                        'page',
                        $request->page ?? 1
                    )
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        // only pdf/doc

        $resource = Resource::store($request);

        return response()->json([
            'data' => new ApiResource($resource),
            'message' => 'Successfully created resource'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        //
    }
}
