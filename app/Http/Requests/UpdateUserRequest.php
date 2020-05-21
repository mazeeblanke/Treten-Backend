<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            // 'as' => ['required', 'string', 'in:student,instructor'],
            // 'phone_number' => ['string'],
            // 'other_name' => ['string', 'max:255'],
            'email' => [
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query
                        ->where('email', request()->user()->email)
                        ->where('provider_id', request()->user()->provider_id)
                        ->where('provider', request()->user()->provider);
                })->ignore(request()->user()->id),
                // 'unique:users,provider,provider_id,email,'.request()->user()->id
            ],
            'password' => ['string', 'min:8'],
        ];
    }
}
