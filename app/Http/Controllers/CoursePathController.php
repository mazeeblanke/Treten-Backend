<?php

namespace App\Http\Controllers;

use App\CoursePath;
use App\Filters\CoursePathCollectionFilters;
use Illuminate\Http\Request;
use App\Http\Resources\CoursePathCollection;

class CoursePathController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CoursePathCollectionFilters $filters)
    {
        return response()->json(new CoursePathCollection(
            CoursePath::withOrderedCourses()
                ->filterUsing($filters)
                ->latest()
                ->paginate(
                    $request->pageSize ?? 8,
                    '*',
                    'page',
                    $request->page ?? 1
                )
        ));
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
     * @param  \App\CoursePath  $coursePath
     * @return \Illuminate\Http\Response
     */
    public function show(CoursePath $coursePath)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CoursePath  $coursePath
     * @return \Illuminate\Http\Response
     */
    public function edit(CoursePath $coursePath)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CoursePath  $coursePath
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CoursePath $coursePath)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CoursePath  $coursePath
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoursePath $coursePath)
    {
        //
    }
}
