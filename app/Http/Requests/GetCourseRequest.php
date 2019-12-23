<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //validate notassigned, must be 1 or 0
            //validate withBatches, must be 1 or 0
            //validate ninstructor, must be numeric and also check that the requester has the right permission to access the information
            //if instructor id then use permissins,
            // isPublished must be 0 or 1

        ];
    }
}
