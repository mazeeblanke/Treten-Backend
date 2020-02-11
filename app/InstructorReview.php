<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Requests\CreateInstructorReviewRequest;
use App\Http\Requests\UpdateInstructorReviewRequest;

class InstructorReview extends Model
{
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
        'author_id',
        'enrollee_id',
        'review_text',
        'course_batch_id',
    ];

    /**
     * The rules to update an instructor review
     *
     * @var array
     */
    public static $updateRules = [
        'rating' => 'nullable|numeric',
        'reviewText' => 'nullable',
    ];

    /**
     * The rules to create an instructor review
     *
     * @var array
     */
    public static $createRules = [
        'rating' => 'required|numeric',
        'courseId' => 'required|numeric',
        'authorId' => 'required|numeric',
        // 'enrolleeId' => 'required|numeric',
        'reviewText' => 'required',
        'courseBatchId' => 'required|numeric',
    ];

    /**
     * This method creates an instructor review
     *
     * @param Request $request
     * @return void
     */
    public static function store(CreateInstructorReviewRequest $request)
    {
        return static::create([
            'approved' => 1,
            'rating' => $request->rating,
            'course_id' => $request->courseId,
            'author_id' => $request->authorId,
            'enrollee_id' => auth()->user()->id,
            'review_text' => $request->reviewText,
            'course_batch_id' => $request->courseBatchId,
        ]);
    }

    /**
     * This method modifies an instructor review
     *
     * @param Request $request
     * @return void
     */
    public function modify(UpdateInstructorReviewRequest $request)
    {
        return $this->update([
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
     * Relationship with author
     *
     * @return void
     */
    public function author()
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

    /**
     * Relationship with course batch
     *
     * @return void
     */
    public function batch()
    {
        return $this->belongsTo(CourseBatch::class, 'course_batch_id');
    }
}
