<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteUserRequest extends FormRequest
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
            'emails' => 'required|array',
            'emails.*' => 'required|email:rfc|distinct|unique:users,email'
        ];
    }

    public function messages()
    {
        return [
            'emails.required' => 'The emails field is required.',
            'emails.array' => 'The emails field must be an array.',
            'emails.*.distinct' => 'This email is a duplicate',
            'emails.*.email' => 'The email is not valid',
            'emails.*.unique' => 'This email has already been taken',
        ];
    }
}
