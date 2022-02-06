<?php

use Illuminate\Support\Facades\Route;

// Modulo Category
Route::group(
    [
        'namespace' => 'App\Modules\Categories\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::group(array('prefix' => 'front', 'middleware' => ['web']), function () {
            Route::get("categories/export", 'FrontCategoriesController@generateExcel');
            Route::get('categories/state/{id}', 'FrontCategoriesController@setChangeState')->where('id', '[0-9]+');
            Route::post("categories/list", 'FrontCategoriesController@getData');
            Route::post("categories/delete-selected", 'FrontCategoriesController@destroySelected');
            Route::resource('categories', 'FrontCategoriesController');
        });
    }
);

