<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        $pageSize = $request->pageSize ?? 6;
        $page = $request->page ?? 1;
        $q = $request->q ?? '';
        $builder = UserGroup::where(function ($builder) use ($q) {
                if ($q) {
                    return $builder
                        ->where('group_name', 'like', '%' . $q . '%');
                }
                return $builder;
            });

        // if ($showActive) {
        //     $builder = $builder->whereStatus('active');
        // }

        // if (\is_numeric($courseId))
        // {
        //     $builder = $builder->join('course_batch_author', 'course_batch_author.author_id', 'users.id')
        //     ->where('course_batch_author.course_id', $courseId)
        //     ->select(\DB::raw('count(course_batch_id) as total_batches, users.*'))
        //     ->groupBy('users.id');
        // }

        $UserGroups = $builder
            ->orderBy('user_groups.created_at', 'desc')
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json((new UserGroupCollection($UserGroups))->additional([
            'message' => 'Successfully fetched user la groups'
        ]));
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
