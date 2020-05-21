<?php

namespace App\Http\Controllers;

use App\CoursePath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Filters\CoursePathCollectionFilters;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'bannerImage' => 'required',
            'name' => 'required'
        ]);

        // store image file
        $path = $request->bannerImage->store('courses');

        // create the post
        CoursePath::create([
            'banner_image' => $path,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return [
            'message' => 'Successfully created'
        ];
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
        $this->validate($request, [
            'bannerImage' => 'required',
            'name' => 'required'
        ]);

        if ($request->hasFile('bannerImage')) {
            // store image file
            $path = $request->bannerImage->store('courses');
            Storage::delete($coursePath->banner_image);
            $coursePath->banner_image = $path;
        }

        $coursePath->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return [
            'message' => 'Successfully updated!',
            'model' => $coursePath->fresh()
        ];
    }

     /**
     * get form fields
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function formFields(Request $request, CoursePath $coursePath)
    {
        return [
            'matrix' => [
                'name' => [
                    'type' => 'text',
                    'placeholder' => 'Enter course path name'
                ],
                'description' => [
                    'type' => 'text',
                    'placeholder' => 'Enter description'
                ],
                'bannerImage' => [
                    'type' => 'image',
                    'placeholder' => 'Enter banner image'
                ]
            ],
            'model' => isset($coursePath) ? $coursePath->toArray()  + [
                'bannerImage' => $coursePath->banner_image ?? null
            ] : null,
            'endpoints' => [
                'createUrl' => route('course-paths.store', [], false),
                'updateUrl' => route('course-paths.update', [
                    'coursePath' => $coursePath->id
                ], false)
            ]
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CoursePath  $coursePath
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoursePath $coursePath)
    {
        $coursePath->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted',
        ]);
    }
}
