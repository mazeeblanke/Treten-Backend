<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseReview;
use App\Filters\CourseReviewCollectionFilters;
use App\Http\Requests\ApproveCourseReviewRequest;
use App\Http\Requests\CreateCourseReviewRequest;
use App\Http\Requests\ListCourseReviewRequest;
use App\Http\Requests\UpdateCourseReviewRequest;
use App\Http\Resources\CourseReview as AppCourseReview;
use App\Http\Resources\CourseReviewCollection;
use Illuminate\Http\Request;

class CourseReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListCourseReviewRequest $request, CourseReviewCollectionFilters $filters)
    {
        return response()->json(
            new CourseReviewCollection(
                CourseReview::with(['enrollee', 'course'])
                    ->filterUsing($filters)
                    ->orderBy(
                        'course_reviews.created_at',
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
    public function store(CreateCourseReviewRequest $request)
    {
        // only a student should be able to create reviews
        $courseReview = CourseReview::store($request);
        return response()->json([
            'message' => 'Successfully created review',
            'data' => new AppCourseReview($courseReview)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CourseReview  $courseReview
     * @return \Illuminate\Http\Response
     */
    public function show(CourseReview $courseReview)
    {
        return response()->json([
            'message' => 'Successfully fetched review',
            'data' => new AppCourseReview($courseReview)
        ]);
    }

    /**
     * Approve the course review
     *
     * @param  \App\CourseReview  $courseReview
     * @return \Illuminate\Http\Response
     */
    public function approve(ApproveCourseReviewRequest $request, CourseReview $courseReview)
    {
        $updatedCourseReview = $courseReview->update([
            'approved' => $request->approved
        ]);

        if ($updatedCourseReview) {
            // $course = $courseReview->course_id;
            $course = Course::find($courseReview->course_id);
            $averageRating = CourseReview::whereCourseId($course->id)
                ->whereApproved(1)
                ->avg('rating');
            // Reset to 2.5 if all ratings have been disapproved
            $course->avg_rating = $averageRating ?? 2.5;
            $course->save();
            return response()->json([
                'message' => 'Successfully approved review',
                'data' => new AppCourseReview($courseReview->load(['enrollee', 'course'])->fresh())
            ]);
        }

        return response()->json([
            'message' => 'Unable to approve review'
        ], 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseReview  $courseReview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseReview $courseReview)
    {
        // if the enrollee id of the rexsource to be updated does not match the currently logged in user abort
        if ($courseReview->modify($request)) {
            return response()->json([
                'message' => 'Successfully updated your review',
                'data' => new AppCourseReview($courseReview->fresh())
            ]);
        }

        return response()->json([
            'message' => 'Unable to update review'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseReview  $courseReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseReview $courseReview)
    {
        // only a student should be able to update reviews
        // if the enrollee id of the rexsource to be updated does not match the currently logged in user abort
        if ($courseReview->delete()) {
            $course = Course::find($courseReview->course_id);
            $averageRating = CourseReview::whereCourseId($course->id)
                ->whereApproved(1)
                ->avg('rating');
            // Reset to 2.5 if all ratings have been disapproved
            $course->avg_rating = $averageRating ?? 2.5;
            $course->save();
            return response()->json([
                'message' => 'Successfully deleted your review',
            ]);
        }

        return response()->json([
            'message' => 'Unable to delete review'
        ]);
    }
}
