<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseBatchAuthor;
use App\CourseEnrollment;
use App\Instructor;
use App\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function dashboardStats(Request $request)
    {
        $stats = [];
        if (!auth()->check()) {
            return response()->json(array_merge($stats, [
                'message' => 'successfully fetched dashboard stats',
            ]));  
        }
        if (auth()->user()->role === 'admin') {
            $stats = [
                'studentsCount' => Student::count(),
                'instructorsCount' => Instructor::count(),
                'coursesCount' => Course::count(),
                'activeClassesCount' => CourseEnrollment::activeClassesCount(),
                'latestEnrollments' => CourseEnrollment::lastFour()
            ];
        }

        if (auth()->user()->role === 'instructor') {
            $stats = [
                'studentsCount' => Instructor::studentsCount(auth()->user()->id),
                'coursesCount' => CourseBatchAuthor::whereAuthorId(auth()->user()->id)
                    ->groupBy('course_id', 'author_id')
                    ->get(['course_id', 'author_id'])
                    ->count(),
            ];
        }

        return response()->json(array_merge($stats, [
            'message' => 'successfully fetched dashboard stats',
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