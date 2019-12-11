<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;

class AdminController extends Controller
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
        $showActive = $request->showActive ?? 0;
        // $courseId= $request->courseId;
        $builder = User::with('details')
            ->whereHas('roles', function ($query) {
                return $query->where('name', 'admin');
            })
            ->where(function ($builder) use ($q) {
                if ($q) {
                    return $builder
                        ->orWhere('first_name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('last_name', 'like', '%' . $q . '%')
                        ->orWhere('phone_number', 'like', '%' . $q . '%')
                        ->orWhere('status', 'like', '%' . $q . '%');
                }
                return $builder;
            });

        if ($showActive) {
            $builder = $builder->whereStatus('active');
        }

        // if (\is_numeric($courseId))
        // {
        //     $builder = $builder->join('course_batch_author', 'course_batch_author.author_id', 'users.id')
        //     ->where('course_batch_author.course_id', $courseId)
        //     ->select(\DB::raw('count(course_batch_id) as total_batches, users.*'))
        //     ->groupBy('users.id');
        // }

        $admins = $builder
            ->orderBy('users.created_at', 'desc')
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json((new UserCollection($admins))->additional([
            'message' => 'Successfully fetched admins'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
