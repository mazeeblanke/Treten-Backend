<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
use App\Student;

class SocialController extends Controller
{
    protected $providers = [
        'facebook',
        'linkedin'
    ];

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
    */
    public function redirectToProvider($provider)
    {
        // dd(request()->redirect);
        if (request()->redirect) {
            session(['redirect' => request()->redirect]);
        }
        $this->validateProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
    */
    public function handleProviderCallback($provider)
    {
        $this->validateProvider($provider);
        $userInfo = Socialite::driver($provider)->user();
        $user = $this->createUser($userInfo, $provider);
        if (!$user->isNotActive()) {
            auth()->login($user);
        }
        $redirect = session()->has('redirect') ? config('app.frontend_url').session()->get('redirect') : config('app.frontend_url');
        // return redirect()->to(request()->return_url ?? config('app.frontend_url'));
        return redirect()->to(request()->return_url ?? $redirect);

        // return redirect()->intended('/');
        // $user->token;
    }

    public function createUser($userInfo, $provider){
        $user = User::where('provider_id', $userInfo->id)->first();
        // $role = Role::firstOrCreate(['name' => 'student'], ['name' => 'student']);
        $names = explode(' ', $userInfo->name);

        $firstName = $names[0] ?? '';
        $lastName = $names[count($names) - 1 ];

        if (!$user) {
            // Student::unsetEventDispatcher();
            request()->request->add([
                'first_name' => $firstName,
                'last_name' => $lastName === $firstName ? '' : $lastName,
                'email'    => $userInfo->email,
                'profile_pic' => $userInfo->avatar_original,
                'provider' => $provider,
                'provider_id' => $userInfo->id
            ]);

            $student = Student::create([]);

            // $student->details()->create();

            // $student->details->assignRole($role);
            // dd($student->details);
            return $student->details;
        }

        // TODO: Maybe caheck the url and determine if the urls start the same befor overridding
        // if ($user->profile_pic !== $userInfo->avatar)
        // {
        //     $user->profile_pic = $userInfo->avatar;
        //     $user->save();
        // }

        return $user->fresh();
    }

    private function validateProvider ($provider)
    {
        if (!in_array($provider, $this->providers))
        {
          return redirect()->back();
        }
    }


}
