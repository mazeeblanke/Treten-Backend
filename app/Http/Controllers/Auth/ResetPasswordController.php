<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request ;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    // protected $resetView = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    // public function showResetForm (Request $request, $token = null)
    // {
    //     // dd($token);
    //     // dd($request->all());
    //     // verify the token exists first and valid
    //     // $this->token;

    //     // if exist show the reset password form

    //     // else show a page that says token invalid
    // }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages()
        );

        $credentials = $this->getResetCredentials($request);

        $broker = $this->getBroker();
        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
            if ($user->isNotActive()) {
                \Auth::logout();
                session()->flush();
            }
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);
            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }

    public function getResetValidationRules ()
    {
        return [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6'
        ];
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        // dd('dfdf');
        // if (request()->wantsJson() && request()->ajax())
        // {
            return response()->json([
                'message' => trans($response)
            ]);
        // }

        // return redirect($this->redirectPath())->with('status', trans($response));
    }


    /**
     * Get the response for after a failing password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(Request $request, $response)
    {
        // if (request()->wantsJson() && request()->ajax())
        // {
            return response()->json([
                'email' => trans($response)
            ], 422);
        // }

        // return redirect()->back()
        //     ->withInput($request->only('email'))
        //     ->withErrors(['email' => trans($response)]);
    }

    public function getResetValidationMessages ()
    {
        return [];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getResetCredentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        return [
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password,
            'token' => $request->token
        ];  
    }


    /**
     * Get the broker to be used during password reset.
     *
     * @return string|null
     */
    public function getBroker()
    {
        return property_exists($this, 'broker') ? $this->broker : null;
    }
}
