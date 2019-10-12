<?php

use Creativeorange\Gravatar\Facades\Gravatar;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::group(['prefix' => 'api'], function() {

    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    Route::get('current_user', function () {
        if (auth()->user()) {
            $user = auth()->user();
            $user['gravatar'] = $user->profile_pic ?? Gravatar::get($user->email);
            return response()->json($user, 200);
        }
        return response()->json(['dd' => 232], 422);
    });

});



// Authentication Routes...
Route::get('auth', 'Auth\LoginController@showLoginForm')->name('login');
// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.resetform');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('see', function () {
    return (auth()->user());
});

Route::get('/home', 'HomeController@index')->name('home');



// //test

// Route::get('contactustest', function () {
//     // $invoice = App\Invoice::find(1);

//     return new ContactUsMail([
//         'email' => 'ewomaukah@yahoo.com',
//         'message' => 'jrek erkj rej rkejrejkrrjke j',
//         'first_name' => 'erjker',
//         'last_name' => 'oie ort'
//     ]);
// });