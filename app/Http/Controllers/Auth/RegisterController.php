<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Student;
use App\Instructor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $this->validate(request(), [
            'as' => ['required', 'string', 'in:student,instructor']
        ], [
            'as.required' => 'The type of user is required'
        ]);

        return Validator::make($data, ($this->getUserType($data['as']))::$rules);
    }


    public function register(Request $request)
    {
        
        if ($this->validator($request->all())->fails())
        {
            $errors = $this->validator($request->all())->errors();
            return response()->json([
                'errors' => $errors
            ], 422);
        }

        event(new Registered($user = $this->create($request->all())));

        if ($request->as === 'student')
        {
            $this->guard()->login($user);
        }
       
        if ($request->wantsJson())
        {
            return response()->json([
                'message' => 'Successfully registered user!',
                'user' => auth()->user()
            ]);
        }

        return redirect()->to($request->return_url ?? config('app.frontend_url'));

        // return $this->registered($request, $user)
        //                 ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // return User::register($data);;
        return ($this->getUserType($data['as']))::register($data);
    }


    protected function getUserType ($entity)
    {
        return "App\\".Str::ucfirst($entity);
    }
}
