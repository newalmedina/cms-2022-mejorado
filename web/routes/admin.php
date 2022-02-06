<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemNotification\AdminSystemNotificationController;


/*
|--------------------------------------------------------------------------
| Web Admin Routes
|--------------------------------------------------------------------------
|
| Estas rutas son exclusivas de la parte del core de admnistración
| Son invocadas desde app/Providers/RouteServiceProvider.php
|
|
*/

// Generales
Route::group(
    [
        'namespace' => 'App\Http\Controllers\Language',
        'middleware' => ['web']
    ],
    function () {
    // Cambio de idioma
    Route::get('/changelanguage/{lang}', 'LanguageController@switchLang');
});



require __DIR__ . '/auth_admin.php';

// Admin General Routes
Route::group(
    [
        'namespace' => 'App\Http\Controllers\DashboardController',
        'middleware' => ['admin'],
        'prefix' => 'admin'
    ],
    function () {

    Route::get('/', 'DashboardController@index')
        ->name('admin')
        ->middleware('admin_password_expired');
    Route::post('dashboard/savestate', 'DashboardController@saveState');
    Route::post('dashboard/changeskin', 'DashboardController@changeSkin');
});

Route::group(
    [
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['admin'],
        'prefix' => 'admin/2fa',
        'as'=>'admin.2fa.'
    ],
    function () {
        Route::post('enable', 'Auth\AdminTwoFactorController@enable2fa' )->name('enable2fa');
        Route::post('disable', 'Auth\AdminTwoFactorController@disable2fa' )->name('disable2fa');

        Route::get('verify/resend', 'Auth\AdminTwoFactorController@resend')->name('verify.resend');
        Route::post('verify', 'Auth\AdminTwoFactorController@store' )->name('verify.store');
        Route::get('verify', 'Auth\AdminTwoFactorController@index' )->name('verify.index');

});

// Module Users
Route::group(
    [
        'namespace' => 'App\Http\Controllers\User',
        'prefix' => 'admin'
    ],
    function () {

        // Ruta para volver al usuario original... Usuario no puede ser adminstrador
        Route::get("users/suplantar/revertir", "AdminSuplantacionController@revertir");

        Route::group(
            [
                'middleware' => ['admin'],
            ],
            function () {

                // Ruta de suplantación...  Tiene que ser administrador
                Route::get("users/suplantar/{id}", "AdminSuplantacionController@suplantar");


                Route::post("users/list", 'AdminUserController@getData');
                Route::get('users/state/{id}', 'AdminUserController@setChangeState')->where('id', '[0-9]+');
                Route::get('users/edit_user/{id}', 'AdminUserController@getUserForm')->where('id', '[0-9]+');
                Route::post('users/exists/login',  'AdminUserController@checkLoginExists');
                Route::post('users/generate/pass',  'AdminUserController@generatePassword');
                Route::get('users/generateExcel', 'AdminUserController@generateExcel');
                Route::get('users/userStats', 'AdminUserController@getUserStats');

                Route::get("users/roles/{id}", 'AdminRolesController@edit')->where('id', '[0-9]+');
                Route::post('users/roles/update', 'AdminRolesController@update');

                Route::get("users/social/{id}", 'AdminSocialController@edit')->where('id', '[0-9]+')->name('users.social.edit');
                Route::patch('users/social/update/{id}', 'AdminSocialController@update')->where('id', '[0-9]+')->name('users.social.update');

                Route::get("users/api/{id}", 'AdminApiTokensController@edit')->where('id', '[0-9]+')->name('admin.users.api.edit');
                Route::patch('users/api/update/{id}', 'AdminApiTokensController@update')->where('id', '[0-9]+')->name('admin.users.api.update');
                Route::delete('users/api/{id}', 'AdminApiTokensController@destroy')->where('id', '[0-9]+')->name('admin.users.api.destroy');

                Route::resource('users', 'AdminUserController');
            }
        );
    }
);

// Module Roles
Route::group(
    [
        'namespace' => 'App\Http\Controllers\Roles',
        'middleware' => ['admin'],
        'prefix' => 'admin'
    ],
    function () {

        Route::post("roles/list", 'AdminRolesController@getData');
        Route::get('roles/state/{id}', 'AdminRolesController@setChangeState')->where('id', '[0-9]+');
        Route::get('roles/edit_role/{id}', 'AdminRolesController@getRoleForm')->where('id', '[0-9]+');

        Route::delete("roles/permissions/{id}/{permission_id}", 'AdminPermissionsController@destroy')->where('id', '[0-9]+')->where('permission_id', '[0-9]+');
        Route::get("roles/permissions/{id}", 'AdminPermissionsController@edit')->where('id', '[0-9]+');
        Route::post('roles/permissions/update', 'AdminPermissionsController@update');

        Route::resource('roles', 'AdminRolesController');
    }
);



// Module User Profile
Route::group(
    [
        'namespace' => 'App\Http\Controllers\Profile',
        'middleware' => ['admin'],
        'prefix' => 'admin'
    ],
    function () {

        Route::get('profile', 'AdminProfileController@edit');
        Route::get('profile/getphoto/{photo}',  'AdminProfileController@getPhoto');
        Route::post('profile/photo',  'AdminProfileController@upload');
        Route::post('profile', 'AdminProfileController@update');
        Route::post('profile/exists/login',  'AdminProfileController@checkLoginExists');

        Route::patch('profile/social/update/{id}', 'AdminProfileController@updateSocial')->where('id', '[0-9]+')->name('profile.social.update');
    }
);


// Module Notificaciones del sistema
Route::group(
    [
        'namespace' => 'App\Http\Controllers\SystemNotification',
        'middleware' => ['admin'],
        'prefix' => 'admin'
    ],
    function () {

        Route::post('notification/mark', [AdminSystemNotificationController::class, 'mark']);
        Route::post('notification/mark_all', [AdminSystemNotificationController::class, 'markAll']);
    }
);


//Admin Module Control de Acceso
Route::group(
    [
        'namespace' => 'App\Http\Controllers\Acceso',
        'middleware' => ['admin'],
        'prefix' => 'admin'
    ],
    function () {

        Route::get('acceso/generateExcel', 'AdminAccesoController@generateExcel');
        Route::post("acceso/list", 'AdminAccesoController@getData');
        Route::resource('acceso', 'AdminAccesoController');
    }
);
