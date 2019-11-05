<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Auth\Request;
use App\Rules\ResetEmail;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validateEmail($request)
    {
        $request->validate(['email' => [ 'required', new ResetEmail ]]);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  App\Http\Controllers\Auth\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($request, $response)
    {
        if ($request->wantsJson()) 
        {
            return response()->json([
                'message' => trans($response)
            ]);
        }
        return back()->with('status', trans($response));
    }


    /**
     * Get the response for a failed password reset link.
     *
     * @param  App\Http\Controllers\Auth\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse($request, $response)
    {
        if ($request->wantsJson())
        {
            return response()->json([
                'email' => trans($response)
            ], 422);
        }

        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }

}
