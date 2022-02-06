<?php

use Illuminate\Support\Facades\Route;

// Modulo Center
Route::group(
    [
        'namespace' => 'App\Modules\Centers\Controllers',
        'middleware' => ['web']
    ],
    function () {

        Route::get("centers/list_centers", "FrontCentersController@listCenters");
        Route::post("centers/list_centers", "FrontCentersController@postCenters");

        Route::group(array('prefix' => 'admin', 'middleware' => ['web']), function () {
            Route::get("centers/export", 'AdminCentersController@generateExcel');
            Route::get('centers/state/{id}', 'AdminCentersController@setChangeState')->where('id', '[0-9]+');
            Route::post("centers/list", 'AdminCentersController@getData');
            Route::post("centers/delete-selected", 'AdminCentersController@destroySelected');
            Route::resource('centers', 'AdminCentersController');
        });
    }
);
