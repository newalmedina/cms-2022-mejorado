<?php

use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => '/api/v1',
        'namespace' => 'App\Modules\Categories\Api\v1',
        'as' => 'api.',
        'middleware' => ['auth:api']
    ],
    function () {
        Route::get('categories/list', 'CategoriesApiController@list')->name('categories.list');
        Route::resource('categories', 'CategoriesApiController', ['except' => ['create', 'edit']]);
    }
);
