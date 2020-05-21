<?php

namespace App\Http\Requests;

use App\CourseReview;
use Illuminate\Foundation\Http\FormRequest;

class CreateCourseReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAStudent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return CourseReview::$createRules;
    }
}
