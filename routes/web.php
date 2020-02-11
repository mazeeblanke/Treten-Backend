<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'api'], function () {
    Route::get('/', 'HomeController@index');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    Route::get('blog-posts', 'BlogPostController@index');
    Route::post('blog-posts', 'BlogPostController@store');
    Route::get('blog-post/{blogpost_slug}', 'BlogPostController@show');
    Route::get('latest-blog-posts', 'BlogPostController@latestBlogPosts');
    Route::get('instructor/{instructor_slug}', 'InstructorController@show');

    Route::get('courses', 'CourseController@index');
    Route::get('courses/{course}', 'CourseController@show');
    Route::post('courses/{course}', 'CourseController@update');
    Route::delete('courses/{course}', 'CourseController@destroy');
    Route::get('popular-courses', 'CourseController@popular');
    Route::get('courses-by-categories', 'CourseController@groupByCategories');
    Route::post('course', 'CourseController@Store');

    Route::get('course-batches', 'CourseBatchController@index');
    Route::post('course-batches', 'CourseBatchController@store');
    Route::patch('course-batches/{courseBatch}', 'CourseBatchController@update');
    Route::delete('course-batches/{courseBatch}', 'CourseBatchController@destroy');
    Route::post('course-batch/{courseBatch}', 'CourseBatchController@update');
    Route::delete('course-batch/{courseBatch}', 'CourseBatchController@destroy');

    Route::post('assign-instructor', 'CourseBatchAuthorController@store');
    Route::delete('delete-schedule/{courseBatchAuthor}', 'CourseBatchAuthorController@destroy');

    Route::get('current_user', 'UserController@currentUser');

    Route::post('invite-users', 'InviteUserController@store');
    Route::post('invitations/complete', 'InviteUserController@complete');

    Route::get('instructors', 'InstructorController@index');
    Route::post('instructor/{instructor}', 'InstructorController@update');
    Route::get('instructor/{instructor_slug}', 'InstructorController@show');

    Route::get('users', 'UserController@index');
    Route::get('students', 'StudentController@index');
    Route::get('admins', 'AdminController@index');
    Route::post('user', 'UserController@update')->middleware('auth');

    Route::get('transactions', 'TransactionController@index');

    Route::apiResource('resources', 'ResourceController');
    Route::apiResource('course-categories', 'CourseCategoryController');
    Route::apiResource('course-reviews', 'CourseReviewController');
    Route::apiResource('user-groups', 'UserGroupController');
    Route::apiResource('testimonials', 'TestimonialController');
    Route::apiResource('course-paths', 'CoursePathController');
    Route::post('course-review-approval/{courseReview}', 'CourseReviewController@approve');
    Route::post('users-activation/{user}', 'UserController@handleActivation');
    Route::apiResource('instructor-reviews', 'InstructorReviewController');

    Route::post('/contactus', 'ContactUsController@store');

    Route::post('/enroll', 'CourseEnrollmentController@store');

    Route::get('/settings', 'SettingController@index');
    Route::post('/settings', 'SettingController@update');

    Route::get('messagethread', 'MessageController@index')->name('messages');
    Route::post('messages', 'MessageController@store')->name('message.store');
    Route::post('broadcasts', 'BroadcastsController@store')->name('broadcast.store');
    Route::get('messagethread/{message_uuid}', 'MessageController@show')->name('message.show');

    Route::get('dashboard-stats', 'DashboardController@dashboardStats')->middleware('auth');
});

// Authentication Routes...
Route::get('auth', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('auth/{provider}', 'Auth\SocialController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialController@handleProviderCallback');

Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
Route::any('/payment/callback', 'PaymentController@handleGatewayCallback');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.resetform');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/invitation/{token}', 'InviteUserController@show');
