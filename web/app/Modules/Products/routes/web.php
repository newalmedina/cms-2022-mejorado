<?php

use Illuminate\Support\Facades\Route;

// Modulo Product
Route::group(
    [
        'namespace' => 'App\Modules\Products\Controllers',
        'middleware' => ['web']
    ],
    function () {
        Route::group(array('prefix' => 'front', 'middleware' => ['web']), function () {
            Route::get("products/export", 'FrontProductsController@generateExcel');
            Route::get('products/state/{id}', 'FrontProductsController@setChangeState')->where('id', '[0-9]+');
            Route::post("products/list", 'FrontProductsController@getData');
            Route::post("products/delete-selected", 'FrontProductsController@destroySelected');
            Route::resource('products', 'FrontProductsController');
        });
    }
);

