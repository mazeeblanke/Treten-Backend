<?php

namespace App\Http\Requests;

use App\NewsLetterSubscription;
use Illuminate\Foundation\Http\FormRequest;

class CreateNewsletterSubscriptionRequest extends FormRequest
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
        return NewsLetterSubscription::$rules;
    }

    // /**
    //  * Get the error messages for the defined validation rules.
    //  *
    //  * @return array
    //  */
    // public function messages()
    // {
    //     // return NewsLetterSubscription::$messages;
    // }
}
