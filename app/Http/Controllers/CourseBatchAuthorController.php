<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseBatchAuthor;
use Illuminate\Http\Request;

class CourseBatchAuthorController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        //verify that coure existss
        $course = Course::whereId($request->courseId)->first();
        // dd($course->instructors);
        if (!$course) {
            return response()->json([
                'message' => 'An error occured',
            ], 400);
        }

        // TODO: verify that the user to be added is an instructor, an admin cant be added
        // if course has already been assigned and you did'nt specify a batch id
        if (is_null($request->batchId)) {
            $alreadyAssigned = CourseBatchAuthor::whereAuthorId($request->authorId)
                ->whereCourseId($request->courseId)
                ->whereActive(1)
                ->exists();
            if ($alreadyAssigned) {
                return response()->json([
                    'message' => 'This instructor has already been assigned to this course, try assigning to a specific batch',
                ], 400);
            }
        }

        $assigned = \DB::table('course_batch_author')->insert([
            'course_id' => $request->courseId,
            'author_id' => $request->authorId,
            'course_batch_id' => $request->batchId,
            'created_at' => now(),
        ]);

        if (!$assigned) {
            return response()->json([
                'message' => 'An error occured',
            ], 400);
        }

        return response()->json([
            'message' => 'Successfully assigned instructor to course',
        ], 200);
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
    public function destroy(CourseBatchAuthor $courseBatchAuthor)
    {
        // dd($courseBatchAuthor);
        $courseBatchAuthor->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted schedule',
        ]);
    }
}
