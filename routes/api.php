<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => []], function () {
    
     //CSV ENDPOINTS
     Route::get('{type}/download', 'DownloadController@downloadcsv');
     
    // Route::get('/u', function () {
    //     dd('sjkdjks');
    // });
    // Route::get('/downlaod', function () {
    //     dd('sjkdjks');
    // });
    // Route::get('/downlaod', 'UserController@downloadcsv');
    
    // NEWSLETTER
    Route::post('/newsletter/subscribe', 'NewsletterController@store');
    Route::post('/newsletter/unsubscribe', 'NewsletterController@destroy');

    // CONTACT US
    Route::post('/contactus', 'ContactUsController@store');

    // BECOME AN INSTRUCTOR
    Route::post('become-an-instructor', 'Auth\RegisterController@register');

});
