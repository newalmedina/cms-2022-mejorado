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

/* Route::get('/', function () {
    if (config("general.only_backoffice", false)) {
        return redirect()->to('admin/');
    } else {
        return view('modules.home.welcome');
    }
})->name('home'); */


// Front Routes
Route::group(
    [
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['web']
    ],
    function () {

        // Home
        Route::get('/', 'Home\FrontHomeController@index')->name('home');

        // Dashboard
        Route::get('dashboard', 'DashboardController\FrontDashboardController@index')
            ->name('dashboard')
            ->middleware('front_password_expired');

        Route::post('dashboard/savestate', 'DashboardController\FrontDashboardController@saveState');
    }
);



require __DIR__.'/auth.php';


// Module User Profile
Route::group(
    [
        'namespace' => 'App\Http\Controllers\Profile',
        'middleware' => ['auth']
    ],
    function () {
        Route::get("profile", "FrontProfileController@edit");
        Route::get('profile/getphoto/{photo}', 'FrontProfileController@getPhoto');
        Route::post('profile', 'FrontProfileController@update')->name('front.profile');
        Route::post('profile/exists/login', 'FrontProfileController@checkLoginExists');

        Route::get("profile/security", "FrontProfileController@editSecurity");
        Route::post('profile/security', 'FrontProfileController@updateSecurity');

    }
);

// Module Notificaciones del sistema
Route::group(
    [
        'namespace' => 'App\Http\Controllers\SystemNotification'
    ],
    function () {
        Route::post('notification/mark', 'FrontSystemNotificationController@mark');
        Route::post('notification/mark_all', 'FrontSystemNotificationController@markAll');
    }
);


Route::group(
    [
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['auth'],
        'prefix' => '2fa',
        'as'=>'2fa.'
    ],
    function () {
        Route::post('enable', 'Auth\FrontTwoFactorController@enable2fa' )->name('enable2fa');
        Route::post('disable', 'Auth\FrontTwoFactorController@disable2fa' )->name('disable2fa');

        Route::get('verify/resend', 'Auth\FrontTwoFactorController@resend')->name('verify.resend');
        Route::post('verify', 'Auth\FrontTwoFactorController@store' )->name('verify.store');
        Route::get('verify', 'Auth\FrontTwoFactorController@index' )->name('verify.index');

});
