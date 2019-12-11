<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInstructorReviewRequest;
use App\Http\Requests\UpdateInstructorReviewRequest;
use App\Http\Resources\InstructorReview as AppInstructorReview;
use App\InstructorReview;
use Illuminate\Http\Request;

class InstructorReviewController extends Controller
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
        $isApproved = (int) $request->isApproved;
        $sort = in_array($request->sort, ['asc', 'desc'])
            ? $request->sort
            : 'desc';

        $builder = InstructorReview::with([
            'enrollee', 
            'course', 
            'batch', 
            'author'
        ])
            ->where(function ($query) use ($q) {
                if ($q) {
                    return $query
                        ->orWhere('users.name', 'like', '%' . $q . '%')
                        ->orWhere('review_text', 'like', '%' . $q . '%')
                        ->orWhere('courses.title', 'like', '%' . $q . '%')
                        ->orWhere('instructor_reviews.created_at', 'like', '%' . $q . '%');
                }
                return $query;
            });

        if ($isApproved) {
            $builder = $builder->whereApproved(1);
        }

        $instructorReviews = $builder
            ->orderBy('instructor_reviews.created_at', $sort)
            ->paginate($pageSize, '*', 'page', $page);

        return response()->json(AppInstructorReview::collection($instructorReviews));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInstructorReviewRequest $request)
    {
        // only a student should be able to create reviews
        $instructorReview = InstructorReview::store($request);
        return response()->json([
            'message' => 'Successfully created review',
            'data' => new AppInstructorReview($instructorReview)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InstructorReview  $instructorReview
     * @return \Illuminate\Http\Response
     */
    public function show(InstructorReview $instructorReview)
    {
        return response()->json([
            'message' => 'Successfully fetched review',
            'data' => new AppInstructorReview($instructorReview)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InstructorReview  $instructorReview
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstructorReviewRequest $request, InstructorReview $instructorReview)
    {
        // if the enrollee id of the rexsource to be updated does not match the currently logged in user abort
        if ($instructorReview->modify($request)) {
            return response()->json([
                'message' => 'Successfully updated your review',
                'data' => new AppInstructorReview($instructorReview->fresh())
            ]);
        }

        return response()->json([
            'message' => 'Unable to update review'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InstructorReview  $instructorReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(InstructorReview $instructorReview)
    {
        // only a student should be able to update reviews
        // if the enrollee id of the rexsource to be updated does not match the currently logged in user abort
        if ($instructorReview->delete()) {
            return response()->json([
                'message' => 'Successfully deleted your review',
            ]);
        }

        return response()->json([
            'message' => 'Unable to delete review'
        ]);
    }
}
