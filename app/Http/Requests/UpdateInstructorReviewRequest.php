<?php

namespace App\Http\Requests;

use App\InstructorReview;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorReviewRequest extends FormRequest
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
        return InstructorReview::$updateRules;
    }
}
