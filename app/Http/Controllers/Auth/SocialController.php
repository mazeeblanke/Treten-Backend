<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
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
        auth()->login($user);
        return redirect()->to(config('app.frontend_url') ?? request()->return_url );

        // $user->token;
    }

    public function createUser($userInfo, $provider){
        $user = User::where('provider_id', $userInfo->id)->first();

        $names = explode(' ', $userInfo->name);

        $firstName = $names[0] ?? '';
        $lastName = $names[count($names) - 1 ];

        if (!$user) {
            Student::unsetEventDispatcher();

            $student = Student::create([]);

            $student->details()->create([
                'first_name'     => $firstName,
                'last_name'     => $lastName === $firstName ? '' : $lastName,
                'email'    => $userInfo->email,
                'profile_pic'    => $userInfo->avatar_original,
                'provider' => $provider,
                'provider_id' => $userInfo->id
            ]);

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
