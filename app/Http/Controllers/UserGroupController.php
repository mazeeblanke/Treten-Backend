<?php

namespace App\Http\Controllers;

use App\Filters\UserGroupCollectionFilters;
use App\UserGroup;
use Illuminate\Http\Request;
use App\Http\Resources\UserGroupCollection;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UserGroupCollectionFilters $filters)
    {
        return response()->json(
            (new UserGroupCollection(
                UserGroup::filterUsing($filters)
                    ->orderBy(
                        'user_groups.created_at',
                        $request->sort ?? 'desc'
                    )
                    ->paginate(
                        $request->pageSize ?? 8,
                        '*',
                        'page',
                        $request->page ?? 1
                    )
            ))->additional([
                'message' => 'Successfully fetched user la groups'
            ])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function show(UserGroup $userGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(UserGroup $userGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserGroup $userGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserGroup  $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGroup $userGroup)
    {
        //
    }
}
