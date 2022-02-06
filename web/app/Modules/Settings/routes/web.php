<?php

use Illuminate\Support\Facades\Route;

// Module Settings
Route::group(
    [
        'module' => 'Settings',
        'middleware' => ['web', 'password.confirm:admin.password.confirm'],
        'namespace' => 'App\Modules\Settings\Controllers'
    ],
    function () {
        Route::group(array('prefix' => 'admin'), function () {
            Route::get("settings", 'AdminSettingsController@index')->name('settings');
            Route::post("settings", 'AdminSettingsController@update');

            Route::get("settings/mail", 'AdminSettingsController@editMail');
            Route::post("settings/mail", 'AdminSettingsController@sendMail');
        });
    }
);
