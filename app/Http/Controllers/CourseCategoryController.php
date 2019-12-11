<?php

namespace App\Http\Controllers;

use App\CourseCategory;
use App\Http\Resources\CourseCategoryCollection;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->pageSize ?? 8;
        $page = $request->page ?? 1;
        $q = $request->q ?? '';
        $courseId = (int) $request->courseId;
        $minimal = isset($request->minimal) ? (int) $request->minimal : null;
        $sort = in_array($request->sort, ['asc', 'desc'])
        ? $request->sort
        : 'desc';

        $builder = CourseCategory::where(function ($query) use ($q) {
            if ($q) {
                return $query->where('name', 'like', '%' . $q . '%');
            }
            return $query;
        });

        // if (!\is_null($minimal) && $minimal === 0) {
        //     $builder = $builder->select('courses.*');
        // }

        $courseCategories = $builder
            ->orderBy('created_at', $sort)
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json(new CourseCategoryCollection($courseCategories));
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
     * @param  \App\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseCategory $courseCategory)
    {
        //
    }
}