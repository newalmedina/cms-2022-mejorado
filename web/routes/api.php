<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/* Version 1 */
Route::group(
    [
        'namespace' => '\App\Http\Controllers\Api',
        'prefix' => 'v1'
    ],
    function () {

    /* Auth */
    Route::group(['prefix' => 'auth'], function () {
        // /api/v1/auth/signin
        Route::post('signin', 'AuthController@signin');
        //Route::post('register', 'AuthController@register');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            // /api/v1/auth/signout
            Route::post('signout', 'AuthController@signout');

            Route::get('need-reset-password', 'AuthController@needResetPassword');
            Route::post('need-reset-password', 'AuthController@changePassword');
            Route::get('verify-authorization-token', 'AuthController@verifyToken');

        });

    });

    /* User */
    Route::group(['prefix' => 'user'], function () {
        Route::post('password/email', 'UserController@postEmail');
        Route::post('password/token', 'UserController@getPasswordToken');
        Route::post('password/reset', 'UserController@resetPassword');
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('profile', 'UserController@getProfile')->name('profile.get');
            Route::patch('/profile', 'UserController@updateProfile')->name('profile.update');
        });
    });

    // Avatar
    Route::group(['prefix' => 'profile'], function () {
        Route::get('avatar/{avatar}', 'ProfileController@getAvatar');

    });
});
