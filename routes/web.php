<?php

use App\Events\done;
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

Route::group(['prefix' => 'api'], function() {

    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    Route::post('blog-posts', 'BlogPostController@store');
    Route::get('blog-posts', 'BlogPostController@index');
    Route::get('latest-blog-posts', 'BlogPostController@latestBlogPosts');

    Route::get('current_user', 'UserController@currentUser');

    Route::get('instructors', 'InstructorController@index');
    Route::get('instructor/{instructor_slug}', 'InstructorController@show');
    Route::post('instructor/{instructor}', 'InstructorController@update');

    Route::get('students', 'StudentController@index');
    Route::get('users', 'UserController@index');
    Route::post('user', 'UserController@update')->middleware('auth');

    Route::get('transactions', 'TransactionController@index'); 

    Route::get('messagethread', 'MessageController@index')->name('messages');
    Route::get('messagethread/{message_uuid}', 'MessageController@show')->name('message.show');
    Route::post('messages', 'MessageController@store')->name('message.store');

    Route::get('dashboard-stats', 'DashboardController@dashboardStats')->middleware('auth');
});



// Authentication Routes...
Route::get('auth', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.resetform');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/fire', function () {
    logger('firing...');
    broadcast(new done());
    logger('done firing ...');
});