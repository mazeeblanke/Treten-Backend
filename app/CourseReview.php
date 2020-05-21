<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Requests\CreateCourseReviewRequest;
use App\Http\Requests\UpdateCourseReviewRequest;


class CourseReview extends Model
{

    use Filterable;
    use SoftDeletes;

    /**
     * The allowed mass assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'rating',
        'approved',
        'course_id',
        'enrollee_id',
        'review_text',
    ];

    /**
     * The rules to update a course review
     *
     * @var array
     */
    public static $updateRules = [
        'rating' => 'nullable|numeric',
        'reviewText' => 'nullable',
    ];

    /**
     * The rules to create a course review
     *
     * @var array
     */
    public static $createRules = [
        'rating' => 'required|numeric',
        'reviewText' => 'required',
        'courseId' => 'required|numeric',
        // 'enrolleeId' => 'required|numeric',
    ];

    /**
     * The rules to approve a course review
     *
     * @var array
     */
    public static $approveRules = [
        'approved' => 'required'
    ];

    /**
     * this method creates a course review
     *
     * @param Request $request
     * @return void
     */
    public static function store(CreateCourseReviewRequest $request)
    {
        return static::create([
            'approved' => 0,
            'rating' => $request->rating,
            'course_id' => $request->courseId,
            'enrollee_id' => auth()->user()->id,
            'review_text' => $request->reviewText,
        ]);
    }

    /**
     * This method modifies a course review
     *
     * @param Request $request
     * @return void
     */
    public function modify($request)
    {
        return $this->update([
            'approved' => 0,
            'rating' => $request->rating ?? $this->rating,
            'review_text' => $request->reviewText ?? $this->reviewText,
        ]);
    }

    /**
     * Relationship with enrollee
     *
     * @return void
     */
    public function enrollee()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with course
     *
     * @return void
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function toCSV()
    {
        $reviews = static::with(['enrollee', 'course'])->get()->map(function ($review) {

            return [
                $review->created_at,
                $review->enrollee->name,
                $review->course->title,
                $review->review_text,
                $review->rating,
                $review->approved ? 'Approved': 'Not Approved',
                $review->courseId,
            ];
        });

        return array_merge([
            [
                'Date',
                'Enrollee Name',
                'Course Title',
                'Review Message',
                'Rating',
                'Status',
                'Course Id'
            ],
        ], $reviews->toArray());
    }
}
